<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PaymentService
{
    public function getAll()
    {
        $query = Payment::with(['tour', 'user', 'createdBy', 'updatedBy']);

        $authUser = AuthHelper::getAuthUser();
        if ($authUser && $authUser->user_type == \App\Models\User::TRAVEL_AGENCY_USER_CODE) {
            $tourIds = \App\Models\Tour::where('created_by', $authUser->id)->pluck('id');
            $query->whereIn('tour_id', $tourIds);
        }

        return $query->latest()->get();
    }

    public function store(array $input): Payment
    {
        $input['created_by'] = Auth::id();
        return Payment::create($input);
    }

    public function show(int $id): Payment
    {
        $query = Payment::with(['tour', 'user']);

        $authUser = AuthHelper::getAuthUser();
        if ($authUser && $authUser->user_type == \App\Models\User::TRAVEL_AGENCY_USER_CODE) {
            $tourIds = \App\Models\Tour::where('created_by', $authUser->id)->pluck('id');
            $query->whereIn('tour_id', $tourIds);
        }

        return $query->findOrFail($id);
    }

    public function delete(int $id): bool
    {
        $payment = $this->show($id);
        $payment->deleted_by = Auth::id();
        $payment->save();
        $payment->delete();
        return true;
    }

    public function datatable()
    {
        $authUser = AuthHelper::getAuthUser();

        $data = Payment::with(['tour', 'user', 'createdBy']);

        if ($authUser && $authUser->user_type == \App\Models\User::TRAVEL_AGENCY_USER_CODE) {
            $tourIds = \App\Models\Tour::where('created_by', $authUser->id)->pluck('id');
            $data->whereIn('tour_id', $tourIds);
        }

        $data->latest();

        return DataTables::of($data)
            ->addColumn('tour_name', function ($row) {
                return $row->tour->name ?? '-';
            })
            ->addColumn('member_name', function ($row) {
                return ($row->user->first_name ?? '') . ' ' . ($row->user->last_name ?? '');
            })
            ->editColumn('amount', function ($row) {
                return number_format($row->amount, 2);
            })
            ->addColumn('action', function ($row) use ($authUser) {
                $actions = '';

                if ($authUser && $authUser->hasPermission('payment-delete')) {
                    $deleteUrl = route('payment.destroy', $row->id);
                    $formId = 'delForm-' . $row->id;

                    $actions .= '<form class="d-inline" id="' . $formId . '" action="' . $deleteUrl . '" method="POST">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="button" class="btn bg-gradient-danger btn-xs mx-1"
                                onclick="confirmDelete(\'' . $formId . '\')">
                            <i class="fa-solid fa-trash"></i> Delete
                        </button>
                    </form>';
                }

                return $actions ?: '-';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}


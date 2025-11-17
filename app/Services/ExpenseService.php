<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ExpenseService
{
    public function getAll()
    {
        $query = Expense::with(['tour', 'createdBy', 'updatedBy']);

        $authUser = AuthHelper::getAuthUser();
        if ($authUser && $authUser->user_type == \App\Models\User::TRAVEL_AGENCY_USER_CODE) {
            $tourIds = \App\Models\Tour::where('created_by', $authUser->id)->pluck('id');
            $query->whereIn('tour_id', $tourIds);
        }

        return $query->latest()->get();
    }

    public function store(array $input): Expense
    {
        $input['created_by'] = Auth::id();
        return Expense::create($input);
    }

    public function show(int $id): Expense
    {
        $query = Expense::with(['tour']);

        $authUser = AuthHelper::getAuthUser();
        if ($authUser && $authUser->user_type == \App\Models\User::TRAVEL_AGENCY_USER_CODE) {
            $tourIds = \App\Models\Tour::where('created_by', $authUser->id)->pluck('id');
            $query->whereIn('tour_id', $tourIds);
        }

        return $query->findOrFail($id);
    }

    public function delete(int $id): bool
    {
        $expense = $this->show($id);
        $expense->deleted_by = Auth::id();
        $expense->save();
        $expense->delete();
        return true;
    }

    public function datatable()
    {
        $authUser = AuthHelper::getAuthUser();

        $data = Expense::with(['tour', 'createdBy']);

        if ($authUser && $authUser->user_type == \App\Models\User::TRAVEL_AGENCY_USER_CODE) {
            $tourIds = \App\Models\Tour::where('created_by', $authUser->id)->pluck('id');
            $data->whereIn('tour_id', $tourIds);
        }

        $data->latest();

        return DataTables::of($data)
            ->addColumn('tour_name', function ($row) {
                return $row->tour->name ?? '-';
            })
            ->editColumn('amount', function ($row) {
                return number_format($row->amount, 2);
            })
            ->addColumn('action', function ($row) use ($authUser) {
                $actions = '';

                if ($authUser && $authUser->hasPermission('expense-delete')) {
                    $deleteUrl = route('expense.destroy', $row->id);
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


<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\PointWithdrawRequest;
use Yajra\DataTables\Facades\DataTables;

class PointWithdrawRequestService
{


    public function getAll()
    {
        return PointWithdrawRequest::with(['user', 'approvedBy'])->latest()->get();
    }

    public function show($id)
    {
        return PointWithdrawRequest::with(['user', 'approvedBy'])->findOrFail($id);
    }

    public function datatable()
    {

        $authUser = AuthHelper::getAuthUser();

        $data = PointWithdrawRequest::with(['user', 'approvedBy'])
            ->when(request()->status, function ($query)  {
                $query->where('status', request()->status);
            })->latest();

        return DataTables::of($data)
            ->addColumn('approved_by_name', function ($row) {
                return $row->approvedBy->first_name . ' ' . $row->approvedBy->last_name;
            })
            ->addColumn('user_name', function ($row) {
                return $row->user->first_name . ' ' . $row->user->last_name;
            })
            ->addColumn('action', function ($row) use($authUser){

                $str = '';

                $update = route('pointWithdrawRequest.changeStatus', $row->id);
                $formIdApprove = 'delForm-A-' . $row->id;
                $formIdReject = 'delForm-R-' . $row->id;

                if($row->status == 'pending'){
                    $str .= '<form class="d-inline" id="' . $formIdApprove . '" action="'.$update.' " method="POST">' .
                        csrf_field() .
                        '<input type="hidden" name="status" value="approved">' .
                        '<button type="submit" class="btn bg-gradient-primary btn-xs mx-1" ><i class="far fa-check"></i> Approve</button>' .
                        '</form>';

                    $str .= '<form class="d-inline" id="' . $formIdReject . '" action="'.$update.' " method="POST">' .
                        csrf_field() .
                        '<input type="hidden" name="status" value="reject">' .
                        '<button type="submit" class="btn bg-gradient-danger btn-xs mx-1" ><i class="far fa-trash-alt"></i> Reject</button>' .
                        '</form>';
                }
                $str .= '<a href="' . route('pointWithdrawRequest.show', $row->id) . '" class="btn bg-gradient-secondary btn-xs mx-1" data-id="' . $row->id . '"><i
                class="fas fa-eye"></i> Details</a>';

                return $str;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}

<?php


namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class UserService
{

    public function datatable()
    {
        $authUser = AuthHelper::getAuthUser();

        $data = User::with(['createdBy', 'updatedBy'])->generalUser()->latest();

        return DataTables::of($data)
            ->addColumn('created_by_name', function ($row) {
                return $row->createdBy->name ?? '-';
            })
            ->addColumn('updated_by_name', function ($row) {
                return $row->updatedBy->name ?? '-';
            })
            ->editColumn('status', function ($row) use($authUser){
                $checkStatus = $row->status ? 'checked' : '';
                $status =  '<div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input change-status-checkbox" id="customSwitch' . $row->id . '" data-id="' . $row->id . '" ' . $checkStatus . '>
                <label class="custom-control-label" for="customSwitch' . $row->id . '"></label>
            </div>';
            if ($authUser->hasPermission('general-user-change-status')) {
                return $status;
            }
            })
            ->addColumn('action', function ($row) {
                return '';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }
}

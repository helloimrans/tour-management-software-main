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
                return $row->createdBy->first_name . ' ' . ($row->createdBy->last_name ?? '') ?? '-';
            })
            ->addColumn('updated_by_name', function ($row) {
                return $row->updatedBy->first_name . ' ' . ($row->updatedBy->last_name ?? '') ?? '-';
            })
            ->editColumn('status', function ($row) use ($authUser) {
                if (!$authUser->hasPermission('general-user-change-status')) {
                    return '-';
                }

                $checked = $row->status ? 'checked' : '';
                $switchId = 'customSwitch' . $row->id;

                return '<div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input change-status-checkbox"
                           id="' . $switchId . '" data-id="' . $row->id . '" ' . $checked . '>
                    <label class="custom-control-label" for="' . $switchId . '"></label>
                </div>';
            })
            ->addColumn('action', function ($row) {
                return '';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }
}

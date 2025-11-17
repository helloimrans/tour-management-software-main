<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class MemberManagementService
{
    public function datatable()
    {
        $authUser = AuthHelper::getAuthUser();

        $data = User::with(['tour', 'createdBy']);

        if ($authUser && $authUser->user_type == \App\Models\User::TRAVEL_AGENCY_USER_CODE) {
            $tourIds = \App\Models\Tour::where('created_by', $authUser->id)->pluck('id');
            $data->whereIn('tour_id', $tourIds);
        }

        $data->where('user_type', User::NORMAL_USER_CODE)->latest();

        return DataTables::of($data)
            ->addColumn('full_name', function ($row) {
                return ($row->first_name ?? '') . ' ' . ($row->last_name ?? '');
            })
            ->addColumn('tour_name', function ($row) {
                return $row->tour->name ?? '-';
            })
            ->editColumn('profile_pic', function ($row) {
                $imageUrl = $row->profile_pic
                    ? \Illuminate\Support\Facades\Storage::url($row->profile_pic)
                    : asset('defaults/noimage/no_img.jpg');
                return '<img src="' . $imageUrl . '" alt="Profile" width="70" height="70" style="object-fit: cover; border-radius: 5px;">';
            })
            ->rawColumns(['profile_pic'])
            ->make(true);
    }
}


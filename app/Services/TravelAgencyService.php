<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class TravelAgencyService
{
    public function getAll()
    {
        return User::with(['createdBy', 'updatedBy'])
            ->travelAgency()
            ->latest()
            ->get();
    }

    public function store(array $input): User
    {
        if (isset($input['profile_pic'])) {
            $input['profile_pic'] = uploadFile($input['profile_pic'], 'profile_pic');
        }

        if (isset($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }

        $input['user_type'] = User::TRAVEL_AGENCY_USER_CODE;
        $input['created_by'] = Auth::id();

        $user = User::create($input);

        try {
            $user->attachRole('travel_agency');
        } catch (\Exception $e) {
            Log::error('Failed to attach travel_agency role: ' . $e->getMessage());
        }

        return $user;
    }

    public function register(array $input): User
    {
        if (isset($input['profile_pic'])) {
            $input['profile_pic'] = uploadFile($input['profile_pic'], 'profile_pic');
        }

        if (isset($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }

        $input['user_type'] = User::TRAVEL_AGENCY_USER_CODE;
        $input['status'] = 0;

        $user = User::create($input);

        try {
            $user->attachRole('travel_agency');
        } catch (\Exception $e) {
            Log::error('Failed to attach travel_agency role: ' . $e->getMessage());
        }

        return $user;
    }

    public function show(int $id): User
    {
        return User::travelAgency()
            ->findOrFail($id);
    }

    public function update(int $id, array $input): User
    {
        $user = User::travelAgency()
            ->findOrFail($id);

        if (isset($input['password']) && !empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }

        if (isset($input['profile_pic'])) {
            if ($user->profile_pic) {
                deleteFile($user->profile_pic);
            }
            $input['profile_pic'] = uploadFile($input['profile_pic'], 'profile_pic');
        }

        $input['updated_by'] = Auth::id();

        $user->update($input);

        return $user->fresh();
    }

    public function delete(int $id): bool
    {
        $user = User::travelAgency()
            ->findOrFail($id);

        if ($user->profile_pic) {
            deleteFile($user->profile_pic);
        }

        $user->deleted_by = Auth::id();
        $user->save();
        $user->delete();

        return true;
    }

    public function datatable()
    {
        $authUser = AuthHelper::getAuthUser();

        $data = User::with(['createdBy', 'updatedBy'])
            ->travelAgency()
            ->latest();

        return DataTables::of($data)
            ->addColumn('created_by_name', function ($row) {
                return $row->createdBy->first_name . ' ' . ($row->createdBy->last_name ?? '') ?? '-';
            })
            ->addColumn('updated_by_name', function ($row) {
                return $row->updatedBy->first_name . ' ' . ($row->updatedBy->last_name ?? '') ?? '-';
            })
            ->addColumn('full_name', function ($row) {
                return ($row->first_name ?? '') . ' ' . ($row->last_name ?? '');
            })
            ->editColumn('profile_pic', function ($row) {
                $imageUrl = $row->profile_pic
                    ? Storage::url($row->profile_pic)
                    : asset('defaults/noimage/no_img.jpg');
                return '<img src="' . $imageUrl . '" alt="Profile" width="70" height="70" style="object-fit: cover; border-radius: 5px;">';
            })
            ->editColumn('status', function ($row) use ($authUser) {
                if (!$authUser->hasPermission('travel-agency-change-status')) {
                    return '-';
                }

                $checked = $row->status ? 'checked' : '';
                $switchId = 'customSwitch' . $row->id;

                return '<div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input change-status-checkbox"
                           id="' . $switchId . '" data-id="' . $row->id . '" data-table="users" data-column="status" ' . $checked . '>
                    <label class="custom-control-label" for="' . $switchId . '"></label>
                </div>';
            })
            ->addColumn('action', function ($row) use ($authUser) {
                $actions = '';

                if ($authUser->hasPermission('travel-agency-update')) {
                    $editUrl = route('travel.agency.edit', $row->id);
                    $actions .= '<a href="' . $editUrl . '" class="btn bg-gradient-primary btn-xs mx-1">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>';
                }

                if ($authUser->hasPermission('travel-agency-delete')) {
                    $deleteUrl = route('travel.agency.destroy', $row->id);
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
            ->rawColumns(['action', 'status', 'profile_pic'])
            ->make(true);
    }

    public function getDashboardStats($userId)
    {
        $user = User::travelAgency()->findOrFail($userId);

        $tourIds = \App\Models\Tour::where('created_by', $userId)->pluck('id');

        return [
            'total_tours' => \App\Models\Tour::where('created_by', $userId)->count(),
            'active_tours' => \App\Models\Tour::where('created_by', $userId)->where('status', 1)->count(),
            'total_participants' => User::whereIn('tour_id', $tourIds)->count(),
        ];
    }
}


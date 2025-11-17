<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\Tour;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class TourService
{
    public function getAll()
    {
        $query = Tour::with(['createdBy', 'updatedBy']);

        $authUser = AuthHelper::getAuthUser();
        if ($authUser && $authUser->user_type == \App\Models\User::TRAVEL_AGENCY_USER_CODE) {
            $query->where('created_by', $authUser->id);
        }

        return $query->latest()->get();
    }

    public function store(array $input): Tour
    {
        if (isset($input['image'])) {
            $input['image'] = uploadFile($input['image'], 'tours');
        }

        $input['created_by'] = Auth::id();

        return Tour::create($input);
    }

    public function show(int $id): Tour
    {
        $query = Tour::query();

        $authUser = AuthHelper::getAuthUser();
        if ($authUser && $authUser->user_type == \App\Models\User::TRAVEL_AGENCY_USER_CODE) {
            $query->where('created_by', $authUser->id);
        }

        return $query->findOrFail($id);
    }

    public function update(int $id, array $input): Tour
    {
        $query = Tour::query();

        $authUser = AuthHelper::getAuthUser();
        if ($authUser && $authUser->user_type == \App\Models\User::TRAVEL_AGENCY_USER_CODE) {
            $query->where('created_by', $authUser->id);
        }

        $tour = $query->findOrFail($id);

        if (isset($input['image'])) {
            if ($tour->image) {
                deleteFile($tour->image);
            }
            $input['image'] = uploadFile($input['image'], 'tours');
        }

        $input['updated_by'] = Auth::id();

        $tour->update($input);

        return $tour->fresh();
    }

    public function delete(int $id): bool
    {
        $query = Tour::query();

        $authUser = AuthHelper::getAuthUser();
        if ($authUser && $authUser->user_type == \App\Models\User::TRAVEL_AGENCY_USER_CODE) {
            $query->where('created_by', $authUser->id);
        }

        $tour = $query->findOrFail($id);

        if ($tour->image) {
            deleteFile($tour->image);
        }

        $tour->deleted_by = Auth::id();
        $tour->save();
        $tour->delete();

        return true;
    }

    public function datatable()
    {
        $authUser = AuthHelper::getAuthUser();

        $data = Tour::with(['createdBy', 'updatedBy']);

        if ($authUser && $authUser->user_type == \App\Models\User::TRAVEL_AGENCY_USER_CODE) {
            $data->where('created_by', $authUser->id);
        }

        $data->latest();

        return DataTables::of($data)
            ->addColumn('created_by_name', function ($row) {
                return $row->createdBy->first_name . ' ' . ($row->createdBy->last_name ?? '') ?? '-';
            })
            ->addColumn('updated_by_name', function ($row) {
                return $row->updatedBy->first_name . ' ' . ($row->updatedBy->last_name ?? '') ?? '-';
            })
            ->editColumn('image', function ($row) {
                $imageUrl = $row->image
                    ? Storage::url($row->image)
                    : asset('defaults/noimage/no_img.jpg');
                return '<img src="' . $imageUrl . '" alt="Tour" width="70" height="70" style="object-fit: cover; border-radius: 5px;">';
            })
            ->editColumn('status', function ($row) use ($authUser) {
                if (!$authUser->hasPermission('tour-change-status')) {
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
            ->addColumn('action', function ($row) use ($authUser) {
                $actions = '';

                if ($authUser->hasPermission('tour-update')) {
                    $editUrl = route('tour.edit', $row->id);
                    $actions .= '<a href="' . $editUrl . '" class="btn bg-gradient-primary btn-xs mx-1">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>';
                }

                if ($authUser->hasPermission('tour-delete')) {
                    $deleteUrl = route('tour.destroy', $row->id);
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
            ->rawColumns(['action', 'status', 'image'])
            ->make(true);
    }
}

<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\RadioStation;
use App\Models\RelaksTv;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RadioStationService
{
    public function validator(array $data, $id = null)
    {
        $rules = [
            'name' => ['nullable', 'required', 'string', 'max:191', 'unique:radio_stations,name,' . $id],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'mimes:jpg,jpeg,png,webp,svg,gif', 'max:5120'],
            'under_maintenence_image' => ['nullable', 'mimes:jpg,jpeg,png,webp,svg,gif', 'max:5120'],
            'live_radio_url' => ['nullable', 'string', 'max:255'],
            'live_youtube_url' => ['nullable', 'string', 'max:255'],
            'thumbnail_image' => ['nullable', 'mimes:jpg,jpeg,png,webp,svg,gif', 'max:5120'],
            'phone_number' => ['nullable', 'string', 'max:255'],
            'whatsapp_number' => ['nullable', 'string', 'max:255'],
            'is_under_maintenence' => ['nullable'],
            'is_relaks_tv' => ['nullable'],
            'youtube_url_1' => ['nullable', 'string', 'max:255'],
            'youtube_url_2' => ['nullable', 'string', 'max:255'],
            'youtube_url_3' => ['nullable', 'string', 'max:255']
        ];

        return Validator::make($data, $rules)->validate();
    }

    public function getAll()
    {
        return RadioStation::with(['createdBy', 'updatedBy'])->latest()->get();
    }

    public function store($input)
    {
        $input['slug'] = Str::slug($input['name']);
        $input['created_by'] = Auth::user()->id ?? null;

        if (isset($input['under_maintenence_image'])) {
            $input['under_maintenence_image'] = uploadFile($input['under_maintenence_image'], 'under_maintenence_image');
        }
        if (isset($input['thumbnail_image'])) {
            $input['thumbnail_image'] = uploadFile($input['thumbnail_image'], 'thumbnail_image');
        }
        if (isset($input['logo'])) {
            $input['logo'] = uploadFile($input['logo'], 'logo');
        }

        $radioStation = RadioStation::create($input);

        if (isset($input['is_relaks_tv']) && $input['is_relaks_tv'] == 1) {
            RelaksTv::create([
                'radio_station_id' => $radioStation->id,
                'youtube_url_1' => $input['youtube_url_1'] ?? null,
                'youtube_url_2' => $input['youtube_url_2'] ?? null,
                'youtube_url_3' => $input['youtube_url_3'] ?? null,
            ]);
        }

        return $radioStation;
    }


    public function show($id)
    {
        return RadioStation::with('relaksTv')->find($id);
    }

    public function update($id, $input)
    {
        $data = RadioStation::find($id);
        $input['slug'] = Str::slug($input['name']);
        $input['updated_by'] = Auth::user()->id ?? null;

        if (isset($input['under_maintenence_image'])) {
            if ($data->under_maintenence_image) {
                deleteFile($data->under_maintenence_image);
            }
            $input['under_maintenence_image'] = uploadFile($input['under_maintenence_image'], 'under_maintenence_image');
        }

        if (isset($input['thumbnail_image'])) {
            if ($data->thumbnail_image) {
                deleteFile($data->thumbnail_image);
            }
            $input['thumbnail_image'] = uploadFile($input['thumbnail_image'], 'thumbnail_image');
        }

        if (isset($input['logo'])) {
            if ($data->logo) {
                deleteFile($data->logo);
            }
            $input['logo'] = uploadFile($input['logo'], 'logo');
        }

        $data->update($input);

        if (isset($input['is_relaks_tv']) && $input['is_relaks_tv'] == 1) {
            RelaksTv::updateOrCreate(
                ['radio_station_id' => $data->id],
                [
                    'youtube_url_1' => $input['youtube_url_1'] ?? null,
                    'youtube_url_2' => $input['youtube_url_2'] ?? null,
                    'youtube_url_3' => $input['youtube_url_3'] ?? null,
                ]
            );
        } else {
            if ($data->relaksTv) {
                $data->relaksTv->forceDelete();
            }
            $data->is_relaks_tv = 0;
            $data->save();
        }

        return $data;
    }


    public function delete($id)
    {
        $data = RadioStation::find($id);
        if (!$data) {
            return response()->json(['message' => 'Record not found'], 404);
        }
        $data->deleted_by = Auth::user()->id ?? null;
        $data->save();
        $data->delete();
        return true;
    }

    public function datatable()
    {

        $authUser = AuthHelper::getAuthUser();

        $data = $this->getAll();

        return DataTables::of($data)
            ->editColumn('thumbnail_image', function ($row) {
                $imageUrl = $row->thumbnail_image ? Storage::url($row->thumbnail_image) : asset('defaults/noimage/no_img.jpg');
                return '<img class="rounded" width="60" src="' . $imageUrl . '" alt="' . $row->name . '">';
            })
            ->editColumn('under_maintenence_image', function ($row) {
                $imageUrl = $row->under_maintenence_image ? Storage::url($row->under_maintenence_image) : asset('defaults/noimage/no_img.jpg');
                return '<img class="rounded" width="60" src="' . $imageUrl . '" alt="' . $row->name . '">';
            })
            ->editColumn('is_under_maintenence', function ($row) {
                return $row->is_under_maintenence == 0 ? 'No' : 'Yes';
            })
            ->editColumn('logo', function ($row) {
                $imageUrl = $row->logo ? Storage::url($row->logo) : asset('defaults/noimage/no_img.jpg');
                return '<img class="rounded" width="60" src="' . $imageUrl . '" alt="' . $row->name . '">';
            })
            ->addColumn('created_by_name', function ($row) {
                return $row->createdBy->name;
            })
            ->addColumn('updated_by_name', function ($row) {
                return $row->updatedBy->name;
            })
            ->editColumn('is_active', function ($row) use ($authUser) {
                $checkStatus = $row->is_active ? 'checked' : '';
                $checkStatus = $row->is_active ? 'checked' : '';
                $status =  '<div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input change-status-checkbox" id="customSwitch' . $row->id . '" data-id="' . $row->id . '" ' . $checkStatus . '>
                            <label class="custom-control-label" for="customSwitch' . $row->id . '"></label>
                         </div>';

                if ($authUser->hasPermission('radio-stations-change-status')) {
                    return $status;
                }
            })
            ->addColumn('action', function ($row) use($authUser){

                $str = '';

                if ($authUser->hasPermission('radio-stations-update')) {
                    $str .= '<a href="' . route('radio.stations.edit', $row->id) . '" class="btn bg-gradient-primary btn-xs mx-1" data-id="' . $row->id . '"><i
                    class="fas fa-edit"></i> EDIT</a>';
                }


                $deleteUrl = route('radio.stations.destroy', $row->id);
                $formId = 'delForm-' . $row->id;

                if ($authUser->hasPermission('radio-stations-delete')) {
                $str .= '<form class="d-inline" id="' . $formId . '" action="' . $deleteUrl . '" method="POST">' .
                    csrf_field() .
                    method_field("DELETE") .
                    '<button type="button" class="btn bg-gradient-danger btn-xs mx-1" onclick="confirmDelete(\'' . $formId . '\')"><i class="far fa-trash-alt"></i> Delete</button>' .
                    '</form>';
                }

                return $str;
            })
            ->rawColumns(['action', 'thumbnail_image', 'logo', 'is_active', 'under_maintenence_image', 'is_under_maintenence'])
            ->make(true);
    }
}

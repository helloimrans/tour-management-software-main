<?php

namespace App\Services;

use App\Models\HomeSliderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class HomeSlideService
{
    public function validator(array $data, $id = null)
    {
        $rules = [
            'radio_station_id' => 'nullable|exists:radio_stations,id',
            'title' => 'required|string|max:255|unique:sliders,title',
            'description' => 'nullable|string',
            'thumbnail_image' => 'nullable|string',
            'weekday' => 'nullable|boolean',
            'local_time' => 'nullable|boolean',
            'usa_time' => 'nullable|boolean',
            'created_by' => 'nullable|exists:users,id',
            'updated_by' => 'nullable|exists:users,id',
            'deleted_by' => 'nullable|exists:users,id',
        ];

        return Validator::make($data, $rules)->validate();
    }

    public function getAll()
    {
        return HomeSliderService::with(['createdBy', 'updatedBy'])->latest()->get();
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

        return homeSliderService::create($input);
    }

    public function show($id)
    {
        return homeSliderService::findOrFail($id);
    }

    public function update($id, $input)
    {
        $data = homeSliderService::find($id);
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

        return $data;
    }

    public function delete($id)
    {
        $data = homeSliderService::find($id);
        $data->deleted_by = Auth::user()->id ?? null;
        $data->save();
        $data->delete();
    }

    public function datatable()
    {
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
            ->editColumn('is_active', function ($row) {
                $checkStatus = $row->is_active ? 'checked' : '';
                $checkStatus = $row->is_active ? 'checked' : '';
                return '<div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input change-status-checkbox" id="customSwitch' . $row->id . '" data-id="' . $row->id . '" ' . $checkStatus . '>
                            <label class="custom-control-label" for="customSwitch' . $row->id . '"></label>
                         </div>';
            })
            ->addColumn('action', function ($row) {
                $str = '<a href="'.route('radio.stations.edit', $row->id).'" class="btn bg-gradient-primary btn-xs mx-1" data-id="' . $row->id . '"><i
                class="fas fa-edit"></i> EDIT</a>';

                $str .= '<button type="button" class="btn bg-gradient-danger btn-xs mx-1" data-id="' . $row->id . '"><i
                    class="fa fa-trash"></i> Delete</button>';

                return $str;
            })
            ->rawColumns(['action', 'thumbnail_image', 'logo', 'is_active', 'under_maintenence_image', 'is_under_maintenence'])
            ->make(true);
    }
}

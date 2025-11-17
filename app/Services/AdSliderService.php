<?php

namespace App\Services;

use App\Models\AdSlider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AdSliderService
{
    public function validator(array $data, $id = null): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:191', 'unique:ad_sliders,title,' . $id],
            'description' => ['nullable', 'string'],
            'thumbnail_image' => ['nullable', 'mimes:jpg,jpeg,png,webp,svg,gif', 'max:5120'],
            'radio_station_id' => ['nullable', 'exists:radio_stations,id']
        ];

        return Validator::make($data, $rules)->validate();
    }

    public function getAll(int $radioStationId)
    {
        return AdSlider::where(['radio_station_id' => $radioStationId])
            ->with(['createdBy', 'updatedBy'])
            ->latest()
            ->get();
    }

    public function store(array $input)
    {
        if (isset($input['thumbnail_image'])) {
            $input['thumbnail_image'] = uploadFile($input['thumbnail_image'], 'sliders');
        }

        $input['created_by'] = Auth::user()->id ?? null;

        return AdSlider::create($input);
    }



    public function show(int $id)
    {
        return AdSlider::with('radioStation')->findOrFail($id);
    }

    public function update(int $id, array $input)
    {
        $data = AdSlider::find($id);
        if (isset($input['thumbnail_image'])) {
            if ($data->thumbnail_image) {
                deleteFile($data->thumbnail_image);
            }
            $input['thumbnail_image'] = uploadFile($input['thumbnail_image'], 'thumbnail_image_image');
        }
        $input['updated_by'] = Auth::user()->id ?? null;

        $data->update($input);

        return $data;
    }

    public function delete(int $id)
    {
        $data = AdSlider::find($id);
        $data->deleted_by = Auth::user()->id ?? null;
        $data->save();
        $data->delete();
        return $data;
    }

    public function datatable(int $radioStationId = null)
    {
        $data = AdSlider::with(['createdBy', 'updatedBy', 'radioStation'])
            ->when($radioStationId, function ($query) use ($radioStationId) {
                $query->where('radio_station_id', $radioStationId);
            })
            ->latest();

        //Filter by radio_station_id
        if (request()->has('radio_station_id') && !empty(request()->radio_station_id)) {
            $data->where('radio_station_id', request()->radio_station_id);
        }

        return DataTables::of($data)
            ->addColumn('created_by_name', function ($row) {
                return $row->createdBy->first_name ?? 'N/A';
            })
            ->addColumn('updated_by_name', function ($row) {
                return $row->updatedBy->first_name ?? 'N/A';
            })
            ->addColumn('radio_station_name', function ($row) {
                return $row->radioStation->name ?? 'N/A';
            })


            ->editColumn('thumbnail_image', function ($row) {
                $imageUrl = $row->thumbnail_image ? Storage::url ($row->thumbnail_image) : asset('defaults/noimage/no_img.jpg');
                $thumbnail_image = '<img src="' . $imageUrl . '" alt="' . htmlspecialchars($row->title) . '" width="70">';
                return $thumbnail_image;
            })

            ->editColumn('is_active', function ($row) {
                $checkStatus = $row->is_active ? 'checked' : '';
                return '<div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input change-status-checkbox" id="customSwitch' . $row->id . '" data-id="' . $row->id . '" ' . $checkStatus . '>
                            <label class="custom-control-label" for="customSwitch' . $row->id . '"></label>
                         </div>';
            })

            ->addColumn('action', function ($row) {
                $editUrl = route('adSlider.edit', $row->id);
                $deleteUrl = route('adSlider.destroy', $row->id);
                $formId = 'delForm-' . $row->id;

                $str = '<a href="' . $editUrl . '" class="btn bg-gradient-primary btn-xs mx-1"><i class="fas fa-edit"></i> Edit</a>';
                $str .= '<form class="d-inline" id="' . $formId . '" action="' . $deleteUrl . '" method="POST">' .
                        csrf_field() .
                        method_field('DELETE') .
                        '<button type="button" class="btn bg-gradient-danger btn-xs mx-1" onclick="confirmDelete(\'' . $formId . '\')"><i class="far fa-trash-alt"></i> Delete</button>' .
                        '</form>';
                return $str;
            })

            ->rawColumns(['action', 'is_active', 'thumbnail_image'])
            ->make(true);
    }
}

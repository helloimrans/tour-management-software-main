<?php

namespace App\Services;

use App\Models\FavouriteMusic;
use App\Models\Music;
use App\Models\Slider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SliderService
{
    public function validator(array $data, $id = null): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:191', 'unique:sliders,title,' . $id],
            'description' => ['nullable', 'string'],
            'thumbnail_image' => ['nullable', 'mimes:jpg,jpeg,png,webp,svg,gif', 'max:5120'],
            'radio_station_id' => ['nullable', 'exists:radio_stations,id'],
            'weekday' => ['nullable', 'array'],
            'local_time' => ['nullable', 'date_format:H:i:s,H:i'],
            'usa_time' => ['nullable', 'date_format:H:i:s,H:i'],
        ];

        return Validator::make($data, $rules)->validate();
    }

    public function getAll(int $radioStationId)
    {
        return Slider::where(['radio_station_id' => $radioStationId])
            ->with(['createdBy', 'updatedBy'])
            ->latest()
            ->get();
    }

    public function store(array $input)
    {
        if (isset($input['thumbnail_image'])) {
            $input['thumbnail_image'] = uploadFile($input['thumbnail_image'], 'sliders');
        }
        $input['weekday'] = json_encode($input['weekday']);
        $input['created_by'] = Auth::user()->id ?? null;

        return Slider::create($input);
    }

    public function storeFavouriteMusic(array $input): FavouriteMusic
    {
        return FavouriteMusic::create($input);
    }


    public function show(int $id)
    {
        return Slider::with('radioStation')->findOrFail($id);
    }

    public function update(int $id, array $input)
    {
        $data = Slider::find($id);
        if (isset($input['thumbnail_image'])) {
            if ($data->thumbnail_image) {
                deleteFile($data->thumbnail_image);
            }
            $input['thumbnail_image'] = uploadFile($input['thumbnail_image'], 'thumbnail_image_image');
        }
        $input['weekday'] = json_encode($input['weekday']);
        $input['updated_by'] = Auth::user()->id ?? null;

        $data->update($input);

        return $data;
    }

    public function delete(int $id)
    {
        $data = Slider::find($id);
        $data->deleted_by = Auth::user()->id ?? null;
        $data->save();
        $data->delete();
        return $data;
    }

    public function datatable(int $radioStationId = null)
    {
        $data = Slider::with(['createdBy', 'updatedBy', 'radioStation'])
            ->when($radioStationId, function ($query) use ($radioStationId) {
                $query->where('radio_station_id', $radioStationId);
            })
            ->forRadioStation()
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
            ->editColumn('weekday', function ($row) {
                $weekDay = json_decode($row->weekday ?? '[]', true);
                if ($weekDay && count($weekDay) > 0) {
                    return collect($weekDay)->map(function ($day) {
                        return '<span class="badge badge-primary">' . htmlspecialchars($day) . '</span>';
                    })->join(' ');
                }
                return '<span class="badge badge-secondary">N/A</span>';
            })

            ->editColumn('thumbnail_image', function ($row) {
                $imageUrl = $row->thumbnail_image ? Storage::url($row->thumbnail_image) : asset('defaults/noimage/no_img.jpg');
                $thumbnail_image = '<img src= "' . $imageUrl . '" alt="' . $row->name . '" width="70">';
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
                $str = '<a href="' . route('slider.edit', $row->id) . '" class="btn bg-gradient-primary btn-xs mx-1" data-id="' . $row->id . '"><i
                class="fas fa-edit"></i> EDIT</a>';
                $deleteUrl = route('slider.destroy', $row->id);
                $formId = 'delForm-' . $row->id;

                $str .= '<form class="d-inline" id="' . $formId . '" action="' . $deleteUrl . '" method="POST">' .
                    csrf_field() .
                    method_field("DELETE") .
                    '<button type="button" class="btn bg-gradient-danger btn-xs mx-1" onclick="confirmDelete(\'' . $formId . '\')"><i class="far fa-trash-alt"></i> Delete</button>' .
                    '</form>';
                return $str;
            })
            ->rawColumns(['action', 'is_active', 'thumbnail_image', 'weekday'])
            ->make(true);
    }
}

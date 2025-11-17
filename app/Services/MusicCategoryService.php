<?php

namespace App\Services;

use App\Models\MusicCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class MusicCategoryService
{
    public function validator(array $data, $id = null)
    {
        $rules = [
            'name' => [
                'nullable',
                'required',
                'string',
                'max:191',
                Rule::unique('music_categories')->ignore($id),
            ],
            'description' => ['nullable', 'string'],
            'radio_station_id' => ['required', 'integer'],
        ];

        return Validator::make($data, $rules)->validate();
    }

    public function getAll($radioStationId)
    {
        return MusicCategory::where(['radio_station_id' => $radioStationId])->with(['createdBy','updatedBy'])->latest()->get();
    }

    public function store($input)
    {
        $input['slug'] = Str::slug($input['name']);
        $input['created_by'] = Auth::user()->id ?? null;


        return MusicCategory::create($input);
    }

    public function show($id)
    {
        return MusicCategory::findOrFail($id);
    }

    public function update($id, $input)
    {
        $data = MusicCategory::find($id);
        $input['slug'] = Str::slug($input['name']);
        $input['updated_by'] = Auth::user()->id ?? null;

        $data->update($input);

        return $data;
    }

    public function delete($id)
    {
        $data = MusicCategory::find($id);
        $data->deleted_by = Auth::user()->id ?? null;
        $data->save();
        $data->delete();
        return $data;
    }




    public function datatable($request)
    {
        $data = MusicCategory::with(['createdBy','updatedBy', 'radioStation:id,name'])->forRadioStation()->latest();

        //Filter by radio_station_id
        if ($request->has('radio_station_id') && !empty($request->radio_station_id)) {
            $data->where('radio_station_id', $request->radio_station_id);
        }

        return DataTables::of($data)
            ->addColumn('created_by_name', function ($row) {
                return $row->createdBy->name;
            })
            ->addColumn('updated_by_name', function ($row) {
                return $row->updatedBy->name;
            })
            ->addColumn('radio_station', function ($row) {
                return $row->radioStation->name;
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
                $str = '<a href="'.route('music.category.edit', $row->id).'" class="btn bg-gradient-primary btn-xs mx-1" data-id="' . $row->id . '"><i
                class="fas fa-edit"></i> EDIT</a>';
                $deleteUrl = route('music.category.destroy', $row->id);
                $formId = 'delForm-' . $row->id;

                $str .= '<form class="d-inline" id="' . $formId . '" action="' . $deleteUrl . '" method="POST">' .
                    csrf_field() .
                    method_field("DELETE") .
                    '<button type="button" class="btn bg-gradient-danger btn-xs mx-1" onclick="confirmDelete(\'' . $formId . '\')"><i class="far fa-trash-alt"></i> Delete</button>' .
                    '</form>';
                return $str;
            })
            ->rawColumns(['action','radio_station'])
            ->make(true);
    }
}

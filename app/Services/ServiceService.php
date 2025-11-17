<?php



namespace App\Services;

use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ServiceService
{
    public function validator(array $data, $id = null)
    {
        $rules = [
            'name' => ['required', 'string', 'max:191', 'unique:services,name,' . $id],
            'radio_station_id' => ['nullable', 'exists:radio_stations,id'],
            'service_category_id' => ['nullable', 'exists:service_categories,id'],
        ];

        return Validator::make($data, $rules)->validate();
    }

    public function getAll($radioStationId)
    {
        return Service::where(['radio_station_id' => $radioStationId])->with(['createdBy', 'updatedBy'])->latest()->get();
    }



    public function store($input)
    {
        $input['slug'] = Str::slug($input['name']);
        $input['created_by'] = Auth::user()->id ?? null;

        return Service::create($input);
    }


    public function show($id)
    {
        $data = Service::with([ 'serviceCategory'])->findOrFail($id);
        return $data;
    }

    public function update($id, $input)
    {
        $data = Service::find($id);
        $input['slug'] = Str::slug($input['name']);
        $input['updated_by'] = Auth::user()->id ?? null;
        $data->update($input);
        return $data;
    }

    public function delete($id)
    {
        $data = Service::find($id);
        $data->deleted_by = Auth::user()->id ?? null;
        $data->save();
        $data->delete();
        return true;
    }

    public function datatable()
    {
        $data = Service::with(['createdBy', 'updatedBy','radioStation', 'serviceCategory'])->latest();
        return DataTables::of($data)
            ->addColumn('created_by_name', function ($row) {
                return $row->createdBy->name;
            })
            ->addColumn('updated_by_name', function ($row) {
                return $row->updatedBy->name;
            })
            ->addColumn('category', function ($row) {
                return $row->serviceCategory->name;
            })
            ->addColumn('radio_station', function ($row) {
                return $row->radioStation->name;
            })
            ->editColumn('is_active', function ($row) {
                $checkStatus = $row->is_active ? 'checked' : '';
                return '<div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input change-status-checkbox" id="customSwitch' . $row->id . '" data-id="' . $row->id . '" ' . $checkStatus . '>
                            <label class="custom-control-label" for="customSwitch' . $row->id . '"></label>
                         </div>';
            })
            ->addColumn('action', function ($row) {
                $editRoute = route('service.edit', $row->id);
                $deleteUrl = route('service.destroy', $row->id);

                $formId = 'delForm-' . $row->id;
                $str='<a href="' . $editRoute . '" class="btn bg-gradient-primary btn-xs mx-1"><i class="fas fa-edit"></i> EDIT</a>';

                $str .= '<form class="d-inline" id="' . $formId . '" action="' . $deleteUrl . '" method="POST">' .
                    csrf_field() .
                    method_field("DELETE") .
                    '<button type="button" class="btn bg-gradient-danger btn-xs mx-1" onclick="confirmDelete(\'' . $formId . '\')"><i class="far fa-trash-alt"></i> Delete</button>' .
                    '</form>';

                return $str;
            })
            ->rawColumns(['action', 'is_active','created_by_name','updated_by_name','category','radio_station'])
            ->make(true);
    }
}

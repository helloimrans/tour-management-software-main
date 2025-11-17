<?php



namespace App\Services;

use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ServiceCategoryService
{
    public function validator(array $data, $id = null)
    {
        $rules = [
            'name' => ['required', 'string', 'max:191', 'unique:service_categories,name,' . $id],
            'radio_station_id' => ['nullable', 'exists:radio_stations,id'],
        ];

        return Validator::make($data, $rules)->validate();
    }

    public function getAll($radioStationId)
    {
        return ServiceCategory::where(['radio_station_id' => $radioStationId])->with(['createdBy', 'updatedBy'])->latest()->get();
    }

    public function store($input)
    {
        $input['slug'] = Str::slug($input['name']);
        $input['created_by'] = Auth::user()->id ?? null;

        return ServiceCategory::create($input);
    }

    public function show($id)
    {
        $data = ServiceCategory::with([ 'radioStation'])->findOrFail($id);
        return $data;
    }

    public function update($id, $input)
    {
        $data = ServiceCategory::find($id);
        $input['slug'] = Str::slug($input['name']);
        $input['updated_by'] = Auth::user()->id ?? null;
        $data->update($input);

        return $data;
    }

    public function delete($id)
    {
        $data = ServiceCategory::find($id);
        $data->deleted_by = Auth::user()->id ?? null;
        $data->save();
        $data->delete();
    }

    public function datatable()
    {
        $data = ServiceCategory::with(['createdBy', 'updatedBy', 'radioStation'])->latest();
        return DataTables::of($data)
            ->addColumn('created_by_name', function ($row) {
                if($row->createdBy) {
                    return $row->createdBy->first_name ?? 'N/A';
                }
                return 'N/A';
            })
            ->addColumn('updated_by_name', function ($row) {
                if($row->updatedBy) {
                    return $row->updatedBy->first_name ?? 'N/A';
                }
                return 'N/A';
            })
            ->addColumn('radio_station_name', function ($row) {
                return $row->radioStation->name ?? 'N/A';
            })
            ->editColumn('is_active', function ($row) {
                $checkStatus = $row->is_active ? 'checked' : '';
                return '<div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input change-status-checkbox" id="customSwitch' . $row->id . '" data-id="' . $row->id . '" ' . $checkStatus . '>
                            <label class="custom-control-label" for="customSwitch' . $row->id . '"></label>
                         </div>';
            })
            ->addColumn('action', function ($row) {
                $editRoute = route('serviceCategory.edit', $row->id);
                $deleteUrl = route('serviceCategory.destroy', $row->id);

                $formId = 'delForm-' . $row->id;
                $str='<a href="' . $editRoute . '" class="btn bg-gradient-primary btn-xs mx-1"><i class="fas fa-edit"></i> EDIT</a>';

                $str .= '<form class="d-inline" id="' . $formId . '" action="' . $deleteUrl . '" method="POST">' .
                    csrf_field() .
                    method_field("DELETE") .
                    '<button type="button" class="btn bg-gradient-danger btn-xs mx-1" onclick="confirmDelete(\'' . $formId . '\')"><i class="far fa-trash-alt"></i> Delete</button>' .
                    '</form>';

                return $str;
            })
            ->rawColumns(['action', 'is_active', 'radio_station_name'])
            ->make(true);
    }
}

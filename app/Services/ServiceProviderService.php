<?php



namespace App\Services;

use App\Models\FavouriteMusic;
use App\Models\Music;
use App\Models\MusicCategory;
use App\Models\Service;
use App\Models\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ServiceProviderService
{
    public function validator(array $data, $id = null)
    {
        $rules = [
            'name' => ['required', 'string', 'max:191', 'unique:service_providers,name,' . $id],
            'logo' => ['nullable', 'mimes:jpg,jpeg,png,webp,svg,gif', 'max:5120'], // Updated MIME types
            'url' => ['nullable', 'url'],
            'radio_station_id' => ['nullable', 'exists:radio_stations,id'],
            'service_id' => ['nullable', 'exists:services,id'],
        ];

        return Validator::make($data, $rules)->validate();
    }

    public function getAll()
    {
        return ServiceProvider::with(['createdBy', 'updatedBy'])->latest()->get();
    }

    public function store($input)
    {
        if (isset($input['logo'])) {
            $input['logo'] = $this->storeFile($input['logo'], 'provider');
        }
        $input['slug'] = Str::slug($input['name']);
        $input['created_by'] = Auth::user()->id ?? null;

        return ServiceProvider::create($input);
    }



    public function show($id)
    {
        $data = ServiceProvider::with([ 'serviceCategory'])->findOrFail($id);
        return $data;
    }

    public function update($id, $input)
    {
        $data = ServiceProvider::find($id);
        if (isset($input['logo'])) {
            if ($data->logo) {
                $this->deleteFile($data->logo);
            }
            $input['logo'] = $this->storeFile($input['logo'], 'provider');
        }
        $input['updated_by'] = Auth::user()->id ?? null;

        $data->update($input);

        return $data;
    }

    public function delete($id)
    {
        $data = ServiceProvider::find($id);
        $data->deleted_by = Auth::user()->id ?? null;
        $data->save();
        $data->delete();
        return true;
    }

    public function datatable()
    {
        $data = ServiceProvider::with(['createdBy', 'updatedBy', 'serviceCategory', 'services','radioStation'])->latest();
        return DataTables::of($data)
            ->addColumn('created_by_name', function ($row) {
                return $row->createdBy->name;
            })
            ->addColumn('updated_by_name', function ($row) {
                return $row->updatedBy->name;
            })
            ->addColumn('service_name', function ($row) {
                if($row->services){
                    return $row->services->name ?? 'N/A';
                }
                return 'N/A';
            })
            ->addColumn('radio_station_name', function ($row) {
                if($row->radioStation){
                    return $row->radioStation->name ?? 'N/A';
                }
                return 'N/A';
            })
            ->addColumn('service_category_name', function ($row) {
                if($row->serviceCategory){
                    return $row->serviceCategory->name ?? 'N/A';
                }
                return 'N/A';
            })
            ->editColumn('logo', function ($row) {
                if($row->logo){
                    $logoUrl = url(Storage::url($row->logo));
                    return '<img src="' . $logoUrl . '" class="img-fluid rounded-circle" width="50" alt="logo">';
                }
                return 'N/A';
            })
            ->editColumn('is_active', function ($row) {
                $checkStatus = $row->is_active ? 'checked' : '';
                return '<div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input change-status-checkbox" id="customSwitch' . $row->id . '" data-id="' . $row->id . '" ' . $checkStatus . '>
                            <label class="custom-control-label" for="customSwitch' . $row->id . '"></label>
                         </div>';
            })
            ->addColumn('action', function ($row) {
                $editRoute = route('serviceProvider.edit', $row->id);
                $deleteUrl = route('serviceProvider.destroy', $row->id);

                $formId = 'delForm-' . $row->id;
                $str='<a href="' . $editRoute . '" class="btn bg-gradient-primary btn-xs mx-1"><i class="fas fa-edit"></i> EDIT</a>';

                $str .= '<form class="d-inline" id="' . $formId . '" action="' . $deleteUrl . '" method="POST">' .
                    csrf_field() .
                    method_field("DELETE") .
                    '<button type="button" class="btn bg-gradient-danger btn-xs mx-1" onclick="confirmDelete(\'' . $formId . '\')"><i class="far fa-trash-alt"></i> Delete</button>' .
                    '</form>';

                return $str;
            })
            ->rawColumns(['action', 'is_active','logo'])
            ->make(true);
    }
    private function storeFile($file, $directory): string
    {
        return $file->store($directory, 'public');
    }

    private function deleteFile($filePath): void
    {
        Storage::delete($filePath);
    }
}

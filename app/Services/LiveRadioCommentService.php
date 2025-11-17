<?php


namespace App\Services;

use App\Models\LiveRadioComment;
use App\Models\MusicCategory;
use App\Models\RadioStation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LiveRadioCommentService
{
    
    public function validator(array $data, $id = null)
    {
        $rules = [
            'radio_station_id' => ['required', 'exists:radio_stations,id'],
            'user_id' => ['required', 'exists:users,id'],
            'comment' => ['required', 'string'],
            'live_radio_url' => ['nullable', 'string'],
        ];
        
        return Validator::make($data, $rules)->validate();
    }
    
    public function getAll($radioStationId = null)
    {
        $data =  LiveRadioComment::select(['id', 'user_id', 'radio_station_id', 'date', 'comment'])
        ->with(['user:id,first_name'])
        ->where('radio_station_id', $radioStationId)
        ->whereDate('date', '>=', now()->startOfDay())
        ->isActive()
        ->latest()
        ->get();

        return $data;
    }
    
    public function store($input)
    {
        $input['date'] = Carbon::now();
        return LiveRadioComment::create($input);
    }
    
    
    
    
    public function show($id)
    {
        return LiveRadioComment::findOrFail($id);
    }
    
    
    public function delete($id)
    {
        $data = LiveRadioComment::find($id);
        $data->deleted_by = Auth::user()->id ?? null;
        $data->save();
        $data->delete();
        return $data;
    }
    
    
    public function datatable(int $radioStationId = null)
    {
        // $data = LiveRadioComment::with(['createdBy', 'updatedBy'])->latest();
        $data = LiveRadioComment::with(['radioStation'])
            ->when($radioStationId, function ($query) use ($radioStationId) {
                $query->where('radio_station_id', $radioStationId);
            })
            ->latest();
            
            return DataTables::of($data)

            ->editColumn('is_active', function ($row) {
                $checkStatus = $row->is_active ? 'checked' : '';
                $checkStatus = $row->is_active ? 'checked' : '';
                return '<div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input change-status-checkbox" id="customSwitch' . $row->id . '" data-id="' . $row->id . '" ' . $checkStatus . '>
                            <label class="custom-control-label" for="customSwitch' . $row->id . '"></label>
                         </div>';
            })
           
            ->addColumn('action', function ($row) {
                $str = '<a href="'.route('user.edit', $row->id).'" class="btn bg-gradient-primary btn-xs mx-1" data-id="' . $row->id . '"><i
                class="fas fa-edit"></i> EDIT</a>';
                $deleteUrl = route('user.destroy', $row->id);
                $formId = 'delForm-' . $row->id;
                
                $str .= '<form class="d-inline" id="' . $formId . '" action="' . $deleteUrl . '" method="POST">' .
                csrf_field() .
                method_field("DELETE") .
                '<button type="button" class="btn bg-gradient-danger btn-xs mx-1" onclick="confirmDelete(\'' . $formId . '\')"><i class="far fa-trash-alt"></i> Delete</button>' .
                '</form>';
                return $str;
            })
            ->rawColumns(['action','is_active'])
            ->make(true);
        
    }
}

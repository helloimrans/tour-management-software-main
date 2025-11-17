<?php


namespace App\Services;

use App\Models\LiveRadioComment;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class LiveCommentService
{

    public function datatable()
    {

        $data = LiveRadioComment::with(['user:id,first_name,last_name', 'radioStation:id,name'])->forRadioStation()->latest();

        //Filter by radio_station_id
        if (request()->has('radio_station_id') && !empty(request()->radio_station_id)) {
            $data->where('radio_station_id', request()->radio_station_id);
        }

        //Filter by date
        if (request()->has('date') && !empty(request()->date)) {
            $data->whereDate('date', request()->date);
        }

        return DataTables::of($data)
            ->addColumn('radio_station', function ($row) {
                return $row->radioStation->name;
            })
            ->addColumn('user_name', function ($row) {
                return $row->user->first_name . ' ' . $row->user->last_name;
            })
            ->editColumn('is_active', function ($row) {
                $checkStatus = $row->is_active ? 'checked' : '';
                return '<div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input change-status-checkbox" id="customSwitch' . $row->id . '" data-id="' . $row->id . '" ' . $checkStatus . '>
                <label class="custom-control-label" for="customSwitch' . $row->id . '"></label>
            </div>';
            })
            ->addColumn('action', function ($row) {
                return '';
            })
            ->rawColumns(['action', 'is_active', 'radio_station', 'user_name'])
            ->make(true);
    }
}

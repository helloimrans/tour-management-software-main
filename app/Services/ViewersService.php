<?php

namespace App\Services;

use App\Models\TotalViewer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ViewersService
{

    public function validator(array $data, $id = null)
    {
        $rules = [
            'viewers' => ['required', 'integer'],
        ];

        return Validator::make($data, $rules)->validate();
    }

    public function getViewers()
    {
        return TotalViewer::sum("viewers");
    }

    public function update($input)
    {
        $viewer = TotalViewer::latest()->first();

        if($viewer){
            return $viewer->update($input);
        }else{
            return TotalViewer::create($input);
        }
    }
}

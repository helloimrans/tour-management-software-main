<?php

namespace App\Services;

use App\Models\PointWithdrawRequest;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PointService
{
    public function validator(array $data, $id = null)
    {
        $rules = [
            'points' => ['required', 'integer'],
        ];

        return Validator::make($data, $rules)->validate();
    }

    public function getPointDetails()
    {
        return Setting::latest()->first();
    }

    public function store($input)
    {
        $input['user_id'] = Auth::user()->id;

        $notify = [
            'heading' => 'Point Withdraw Request',
            'text' => "You have a new point withdraw request.",
            'url' => route('pointWithdrawRequest'),
        ];

        $adminUsers = User::adminUser()->get();

        foreach ($adminUsers as $admin) {
            $admin->notify(new GeneralNotification($notify));
        }

        return PointWithdrawRequest::create($input);
    }
}

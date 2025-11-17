<?php

namespace App\Services;

use App\Models\Music;
use App\Models\RadioStation;
use App\Models\Service;
use App\Models\User;

class DashboardService
{
    public function getDashboardData()
    {
        return [
            'total_members' => User::where('user_type', User::NORMAL_USER_CODE)->count(),
            'radio_stations' => RadioStation::count(),
            'total_music' => Music::count(),
            'total_services' => Service::count(),
        ];
    }
}

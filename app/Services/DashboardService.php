<?php

namespace App\Services;

class DashboardService
{
    public function getDashboardData()
    {
        return [
            'total_members' => 150,
            'tours' => 25,
        ];
    }
}

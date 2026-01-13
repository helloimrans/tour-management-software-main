<?php

namespace App\Services;

use App\Models\Tour;
use App\Models\User;
use App\Models\TourMember;
use App\Models\Expense;

class DashboardService
{
    public function getDashboardData()
    {
        $totalTours = Tour::count();
        $activeTours = Tour::whereIn('status', ['ongoing', 'upcoming'])->count();
        $totalParticipants = TourMember::where('join_status', 'approved')->count();
        $totalMembers = User::where('user_type', User::NORMAL_USER_CODE)->count();
        $totalExpenses = Expense::sum('amount');

        return [
            'total_tours' => $totalTours,
            'active_tours' => $activeTours,
            'total_participants' => $totalParticipants,
            'total_members' => $totalMembers,
            'total_expenses' => $totalExpenses,
        ];
    }
}

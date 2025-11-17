<?php

namespace App\Services;

use App\Models\Tour;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MemberService
{
    public function register(array $input): User
    {
        if (isset($input['profile_pic'])) {
            $input['profile_pic'] = uploadFile($input['profile_pic'], 'profile_pic');
        }

        if (isset($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }

        $input['user_type'] = User::NORMAL_USER_CODE;
        $input['status'] = 1;

        return User::create($input);
    }

    public function updateProfile(int $userId, array $input): User
    {
        $user = User::findOrFail($userId);

        if (isset($input['profile_pic'])) {
            if ($user->profile_pic) {
                deleteFile($user->profile_pic);
            }
            $input['profile_pic'] = uploadFile($input['profile_pic'], 'profile_pic');
        }

        $input['updated_by'] = $userId;
        $user->update($input);

        return $user->fresh();
    }

    public function getDashboardData(int $userId)
    {
        $user = User::findOrFail($userId);
        $currentTour = $user->tour;
        $tourHistory = User::where('id', $userId)
            ->whereNotNull('tour_id')
            ->with('tour')
            ->get();

        return [
            'user' => $user,
            'currentTour' => $currentTour,
            'totalTours' => $tourHistory->count(),
        ];
    }

    public function getAvailableTours()
    {
        return Tour::where('status', 1)
            ->with('createdBy')
            ->latest()
            ->get();
    }

    public function joinTour(int $userId, int $tourId)
    {
        $user = User::findOrFail($userId);
        $tour = Tour::findOrFail($tourId);

        if ($tour->status != 1) {
            throw new \Exception('This tour is not available for joining.');
        }

        if ($user->tour_id == $tourId) {
            throw new \Exception('You are already in this tour.');
        }

        $user->tour_id = $tourId;
        $user->save();

        return $user;
    }

    public function getCurrentTour(int $userId)
    {
        $user = User::with('tour')->findOrFail($userId);

        return [
            'user' => $user,
            'tour' => $user->tour,
        ];
    }

    public function getTourHistory(int $userId)
    {
        $user = User::findOrFail($userId);

        $tours = collect();

        if ($user->tour_id) {
            $currentTour = Tour::with('createdBy')->find($user->tour_id);
            if ($currentTour) {
                $tours->push($currentTour);
            }
        }

        return $tours;
    }
}


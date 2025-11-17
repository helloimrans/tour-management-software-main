<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;


class CouponController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {
            $user = User::with('point:id,user_id,points')
                ->where('id', auth()->user()->id)
                ->select('id', 'own_coupon_code')
                ->first();

            return $this->ResponseSuccess($user, 'Data fetched successfully!');
        } catch (\Throwable $e) {
            return $this->ResponseError($e->getMessage());
        }
    }
}

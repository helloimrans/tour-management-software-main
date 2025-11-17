<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Classes\AuthHelper;
use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Models\CouponPointHistory;
use App\Models\Point;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|unique:users,phone',
                'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
                'password_confirmation' => 'min:6'
            ]);

            if ($validator->fails()) {
                return $this->ResponseError($validator->errors());
            }

            $generatedCouponCode = Str::random(10);

            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $input['own_coupon_code'] = $generatedCouponCode;
            $input['user_type'] = User::NORMAL_USER_CODE;
            $user = User::create($input);

            $setting = Setting::latest()->first();

            // Store ref coupon code
            if ($request->ref_coupon_code) {
                $checkCoupon = User::where('own_coupon_code', $request->ref_coupon_code)->first();
                if ($checkCoupon) {
                    $user->update([
                        'used_coupon_code' => $request->ref_coupon_code
                    ]);

                    $pointPerCoupon = $setting->point_per_coupon ?? 0;
                    $couponPointHistoryData = [
                        'user_id' => $user->id,
                        'type' => CouponPointHistory::BY_REFERENCE,
                        'ref_user_id' => $checkCoupon->id,
                        'coupon_code' => $request->ref_coupon_code,
                        'points' => $pointPerCoupon
                    ];

                    $couponPointData = [
                        'user_id' => $checkCoupon->id,
                        'points' => $pointPerCoupon
                    ];

                    $this->insertUserPoints($couponPointData, $couponPointHistoryData);
                } else {
                    DB::rollBack();
                    return $this->ResponseError("Invalid Coupon Code!");
                }
            }

            if ($setting->is_point_by_registration == 1) {
                $pointPerRegistration = $setting->point_per_registration ?? 0;

                $regiPointHistoryData = [
                    'user_id' => $user->id,
                    'type' => CouponPointHistory::BY_REGISTRATION,
                    'points' => $pointPerRegistration
                ];

                $regiPointData = [
                    'user_id' => $user->id,
                    'points' => $pointPerRegistration
                ];

                $this->insertUserPoints($regiPointData, $regiPointHistoryData);
            }

            DB::commit();

            $user['token'] = $user->createToken('RelaksMedia')->plainTextToken;

            $user['profile_pic'] = $user->profile_pic ? $user->profile_pic_url : null;
            $user->makeHidden('profile_pic_url');

            return $this->ResponseSuccess($user, 'User registered successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->ResponseError($e->getMessage());
        }
    }


    function insertUserPoints($pointData, $pointHistoryData)
    {
        CouponPointHistory::create($pointHistoryData);

        $point = Point::firstOrCreate(
            ['user_id' => $pointData['user_id']],
            ['points' => 0]
        );
        $point->points += $pointData['points'];
        $point->save();
    }

    public function login(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->ResponseError($validator->errors());
            }

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();

                if ($user->status == 1) {
                    $user['token'] = $user->createToken('RelaksMedia')->plainTextToken;

                    $user['profile_pic'] = $user->profile_pic ? $user->profile_pic_url : null;
                    $user->makeHidden('profile_pic_url');


                    return $this->ResponseSuccess($user, 'User logged in successfully.');
                } else {
                    return $this->ResponseError('Your account is not active.');
                }
            } else {
                return $this->ResponseError('Unauthorized.');
            }
        } catch (Exception $e) {
            return $this->ResponseError($e->getMessage());
        }
    }


    public function forgotPasswordSendOtp(Request $request)
    {
        try {
            $user = User::where('email', '=', $request->email)->first();
            if (!$user) {
                return $this->ResponseError('Your phone is not registered, please sign up.');
            }

            $existingOtps = VerificationCode::where('phone_or_email', $request->email)
                ->where('expired_at', '>=', Carbon::now())
                ->where('is_expired', 0)
                ->count();

            if ($existingOtps) {
                return $this->ResponseError('Please try again after 2 minutes.');
            }

            $otp = VerificationCode::generateOTP($request->email);

            try {
                $mailData = ['otp' => $otp->code];
                Mail::to($request->email)->send(new SendOtpMail($mailData));
            } catch (\Throwable $emailException) {
                Log::error('Failed to send OTP email: ' . $emailException->getMessage());
            }

            return $this->ResponseSuccess(['otp' => $otp], 'OTP sent successfully.');
        } catch (\Throwable $exception) {
            return $this->ResponseError($exception->getMessage());
        }
    }

    public function forgotPasswordVerifyOtp(Request $request)
    {
        try {
            VerificationCode::verifyOtp($request->email, $request->code);

            if ($request->is_forgot_password == 1) {
                $token = Str::random(64);
                DB::table('password_reset_tokens')->insert([
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
                return $this->ResponseSuccess($token,'OTP Verified Successfully');
            }

            return $this->ResponseSuccess('OTP Verified Successfully');
            return $this->ResponseSuccess($token,'OTP Verified Successfully');
        } catch (\Throwable $exception) {
            return $this->ResponseError($exception->getMessage());
        } catch (BadRequestException $exception) {
            return $this->ResponseError($exception->getMessage());
        }
    }

    public function setNewPassword(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'password' => 'min:6|required|same:confirm_password',
                'confirm_password' => 'required|min:6',
                'token' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->ResponseError($validator->errors());
            }

            $isToken = DB::table('password_reset_tokens')->where(['token' => $request->token])->first();
            if (!$isToken) {
                return $this->ResponseError('Invalid Token.');
            }

            //Check token time
            $tokenCreatedAt = Carbon::parse($isToken->created_at);
            $expiryTime = $tokenCreatedAt->addMinutes(5);
            $currentTime = Carbon::now();
            if (!$currentTime->lt($expiryTime)) {
                return $this->ResponseError('Token has expired.');
            }

            $phone = $isToken->email;
            User::where('email', $phone)
                ->update(['password' => Hash::make($request->password)]);

            DB::table('password_reset_tokens')->where(['email' => $phone])->delete();

            return $this->ResponseSuccess('Successfully Password Changed.');
        } catch (\Throwable $exception) {
            return $this->ResponseError($exception->getMessage());
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone' => 'nullable|unique:users,phone,' . $user->id,
                'password' => 'nullable|min:6|required_with:password_confirmation|same:password_confirmation',
                'password_confirmation' => 'nullable|min:6'
            ]);

            if ($validator->fails()) {
                return $this->ResponseError($validator->errors());
            }

            $input = $request->all();

            // Update password if provided
            if ($request->filled('password')) {
                $input['password'] = bcrypt($request->password);
            } else {
                unset($input['password']);
            }


            if (isset($input['profile_pic'])) {
                if ($user->profile_pic) {
                    deleteFile($user->profile_pic);
                }
                $input['profile_pic'] = uploadFile($input['profile_pic'], 'profile_pic');
            }

            // Update user's profile
            $user->update($input);

            $user['profile_pic'] = $user->profile_pic ? $user->profile_pic_url : null;
            $user->makeHidden('profile_pic_url');

            return $this->ResponseSuccess($user, 'Profile updated successfully.');
        } catch (Exception $e) {
            return $this->ResponseError($e->getMessage());
        }
    }
}

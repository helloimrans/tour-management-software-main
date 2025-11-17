<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\HomePageController;
use App\Http\Controllers\Api\LiveRadioCommentController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\RadioStationController;
use App\Http\Controllers\Api\MusicCategoryController;
use App\Http\Controllers\Api\MusicController;
use App\Http\Controllers\Api\PointController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\ViewersController;

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/forgot-password/send-otp', 'forgotPasswordSendOtp');
    Route::post('/forgot-password/verify-otp', 'forgotPasswordVerifyOtp');
    Route::post('/forgot-password/set-password', 'forgotPasswordSetPassword');
    Route::post('/forgot-password/set-password', 'forgotPasswordSetPassword');
    Route::post('/set-new-password', 'setNewPassword');
});

Route::get('/settings', [SettingsController::class, 'index']);

Route::get('/home-page', [HomePageController::class, 'index']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('/profile/update', [AuthController::class, 'updateProfile']);

    Route::apiResource('/radio-stations', RadioStationController::class);
    Route::get('/music-category', [MusicCategoryController::class, 'index']);
    Route::get('/music-category/{id}', [MusicCategoryController::class, 'show']);

    Route::get('/music', [MusicController::class, 'index']);
    Route::get('/music/{id}', [MusicController::class, 'show']);
    Route::get('/music-by-category/{music_category_id}', [MusicController::class, 'musicByCategory']);
    Route::post('/favourite-music/store', [MusicController::class, 'storeFavouriteMusic']);
    Route::get('/favourite-music', [MusicController::class, 'getFavouriteMusic']);

    Route::post('/comment/store', [LiveRadioCommentController::class, 'store']);

    Route::get('/total-viewers', [ViewersController::class, 'index']);
    Route::get('/comments', [LiveRadioCommentController::class, 'index']);
    Route::get('/get-menus', [MenuController::class, 'index']);

    Route::get('/service-categories', [ServiceController::class, 'getServiceCategories']);
    Route::get('/my-store', [CouponController::class, 'index']);
    Route::get('/point-details', [PointController::class, 'details']);
    Route::post('/point-withdraw-request', [PointController::class, 'store']);
    Route::get('/service-provider/{service_id}', [ServiceController::class, 'getServiceProviders']);
});



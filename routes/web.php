<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdSliderController;
use App\Http\Controllers\Admin\LiveCommentController;
use App\Http\Controllers\Admin\MusicCategoryController;
use App\Http\Controllers\Admin\MusicController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PointWithdrawRequestController;
use App\Http\Controllers\Admin\RadioStationController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SendMailController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ServiceProviderController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\StatusController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ViewersController;
use App\Http\Controllers\User\ValidationController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

//Clear Cache
Route::get('clear', function () {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('storage:link');
    return 'Cache Cleared Successfully'; //Return anything
});

//Remote validation
Route::post('check-availability', [ValidationController::class, 'checkAvailability'])->name('check-availability')->middleware('auth');
Route::post('check-old-password', [ValidationController::class, 'checkOldPassword'])->name('check-old-password')->middleware('auth');


Route::group(['middleware' => ['web']], function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('show.login.form');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('admin.login');
    Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');
});

Route::group(['prefix' => 'dashboard', 'as' => 'admin.', 'middleware' => ['auth']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
});

Route::group(['prefix' => 'notification', 'as' => 'notification.', 'middleware' => ['auth']], function () {
    Route::get('/get-all-notifications', [NotificationController::class, 'getAllNotifications'])->name('get.all.notifications');
    Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('mark.all.as.read');
    Route::post('/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('mark.as.read');
    Route::post('/remove/{id}', [NotificationController::class, 'remove'])->name('remove');
});

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth']], function () {

    //Role permission start
    Route::get('roles/{role}/permissions', [RoleController::class, 'rolePermissionIndex'])->name('roles.permissions');
    Route::post('roles/{role}/permissions', [RoleController::class, 'rolePermissionSync'])->name('roles.permission-sync');
    Route::get('roles/datatable', [RoleController::class, 'getDatatable'])->name('roles.datatable');
    Route::get('permissions/datatable', [PermissionController::class, 'getDatatable'])->name('permissions.datatable');

    Route::resources([
        'roles' => RoleController::class,
        'permissions' => PermissionController::class,
    ]);

     //Role permission end

    //Global status change
    Route::post('/change-status', [StatusController::class, 'changeStatus'])->name('change.status');

    Route::get('/setting', [SettingsController::class, 'index'])->name('setting');
    Route::post('/setting/update', [SettingsController::class, 'update'])->name('setting.update');

    Route::get('/viewer', [ViewersController::class, 'index'])->name('viewer');
    Route::post('/viewer/update', [ViewersController::class, 'update'])->name('viewer.update');

    //Profile edit start
    Route::get('/edit-profile', [UserController::class, 'editProfile'])->name('edit.profile');
    Route::post('/update-profile-otp-send', [UserController::class, 'updateProfileOtpSend'])->name('update-profile-otp-send');
    Route::post('/update-profile-otp-verify', [UserController::class, 'updateProfileOtpVerify'])->name('update-profile-otp-verify');
    Route::put('/update-profile', [UserController::class, 'updateProfile'])->name('update.profile');
    Route::put('/update-password', [UserController::class, 'updatePassword'])->name('update.password');


    Route::resource('general-users', UserController::class)->names('general.user');
    Route::resource('admin-users', AdminUserController::class)->names('admin.user');

    Route::resource('radio-stations', RadioStationController::class)->names('radio.stations');
    Route::resource('music-categorys', MusicCategoryController::class)->names('music.category');
    Route::resource('music', MusicController::class)->names('music');

    Route::get('live-comments',[ LiveCommentController::class, 'index'])->name('live.comments');

    Route::get('point-withdraw-request',[ PointWithdrawRequestController::class, 'index'])->name('pointWithdrawRequest');
    Route::post('point-withdraw-request/{id}',[ PointWithdrawRequestController::class, 'changeStatus'])->name('pointWithdrawRequest.changeStatus');
    Route::get('point-withdraw-request/show/{id}',[ PointWithdrawRequestController::class, 'show'])->name('pointWithdrawRequest.show');

    Route::resource('sliders', SliderController::class)->names('slider');

    Route::resource('ad-sliders', AdSliderController::class)->names('adSlider');


    Route::resource('service', ServiceController::class)->names('service');
    Route::resource('service-category', ServiceCategoryController::class)->names('serviceCategory');
    Route::resource('service-provider', ServiceProviderController::class)->names('serviceProvider');

    Route::get('/send-mail/list', [SendMailController::class, 'index'])->name('send.mail.list');
    Route::post('/send-mail/store', [SendMailController::class, 'store'])->name('send.mail.store');

});



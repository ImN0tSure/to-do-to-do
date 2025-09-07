<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabinetController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TasklistController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserInfoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'guest'], function () {
    Route::get('/', function () {
        return view('main');
    })->name('main');
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('authorize', [AuthController::class, 'authorizeUser'])
        ->name('authorize');
    Route::get('registration', [RegistrationController::class, 'registration'])
        ->name('registration');
    Route::post('tmp-save-user', [RegistrationController::class, 'tmpSaveUser'])
        ->name('tmp-save-user');
    Route::get('fulfill-profile', [RegistrationController::class, 'fulfillProfile'])
        ->name('fulfill-profile');
    Route::post('register-user', [RegistrationController::class, 'registerUser'])
        ->name('register-user');
});


Route::group(['middleware' => 'auth'], function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::group(['middleware' => 'project.participant'], function () {
        Route::get('project/create', [ProjectController::class, 'create'])
            ->withoutMiddleware('project.participant')
            ->name('project.create');
        Route::post('project', [ProjectController::class, 'store'])
            ->withoutMiddleware('project.participant')
            ->name('project.store');
        Route::resource('project', ProjectController::class)
            ->except(['create', 'store']);
        Route::resource('project/{project}/tasklist', TasklistController::class);
        Route::resource('project/{project}/task', TaskController::class);
    });
    Route::get('cabinet', [CabinetController::class, 'index'])
        ->name('cabinet');
    Route::resource('user', UserController::class);
    Route::resource('user-info', UserInfoController::class);
    Route::post('invite-participant', [InvitationController::class, 'create'])
        ->name('invite-participant');
    Route::put('accept-invitation', [InvitationController::class, 'accept'])
        ->name('accept-invitation');
    Route::put('decline-invitation', [InvitationController::class, 'decline'])
        ->name('decline-invitation');
    Route::get('notifications', [NotificationController::class, 'index'])
        ->name('notifications');
    Route::delete('notifications/{notification}', [NotificationController::class, 'delete'])
        ->where('notification', '[0-9]+');
});

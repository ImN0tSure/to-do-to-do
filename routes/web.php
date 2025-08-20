<?php

use App\Http\Controllers\CabinetController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TasklistController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
    Route::post('authorize', [AuthController::class, 'authorizeUser'])->name('authorize');
});


Route::group(['middleware' => 'auth'], function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::group(['middleware'=> 'project.participant'], function () {
        Route::resource('project', ProjectController::class);
        Route::resource('project/{project}/tasklist', TasklistController::class);
        Route::resource('project/{project}/task', TaskController::class);
    });
    Route::get('cabinet', [CabinetController::class, 'index'])->name('cabinet');
    Route::resource('user', UserController::class);
});

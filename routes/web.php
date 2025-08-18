<?php

use App\Http\Controllers\CabinetController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TasklistController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('main');
})->name('main');
//Route::group(['prefix' => 'project'], function () {
//    Route::resource('', ProjectController::class);
//    Route::resource('{project}/tasklist', TasklistController::class);
//    Route::resource('{project}/task', TaskController::class);
//});
Route::resource('project', ProjectController::class);
Route::resource('project/{project}/tasklist', TasklistController::class);
Route::resource('project/{project}/task', TaskController::class);

Route::get('login', [UserController::class, 'login'])->name('login');
Route::post('auth', [UserController::class, 'auth'])->name('auth');
Route::resource('user', UserController::class);

Route::get('cabinet', [CabinetController::class, 'index'])->name('cabinet');

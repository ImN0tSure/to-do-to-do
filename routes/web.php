<?php

use App\Http\Controllers\CabinetController;
use App\Http\Controllers\ProjectsController;
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
    return view('welcome');
});
Route::get('/cabinet', [CabinetController::class, 'index']);
Route::group(['prefix' => 'project'], function () {
    Route::resource('/', ProjectsController::class);
    Route::resource('{project}/tasklist', TasklistController::class);
    Route::resource('{project}/task', TaskController::class);
});
Route::resource('user', UserController::class);

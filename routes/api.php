<?php

use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', function (Request $request) {
        if (!$request->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $request->user();
    });
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/tasks-for-today', [TaskController::class, 'index']);
});

Route::post('tmp-save-user', [RegistrationController::class, 'tmpSaveUser']);
Route::post('register-user', [RegistrationController::class, 'registerUser']);


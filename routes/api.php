<?php

use App\Http\Controllers\Api\ProjectTaskController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TasklistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserTaskController;
use App\Http\Controllers\Api\ProjectParticipantController;

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

    Route::get('/tasks-for-today', [UserTaskController::class, 'index']);

    Route::get('/projects', [ProjectController::class, 'index']);

    Route::group(['middleware' => 'project.participant'], function () {
        Route::get('/projects/{project}', [ProjectController::class, 'show']);
        Route::get('/project/{project}/participants', [ProjectParticipantController::class, 'index']);
        Route::get('/project/{project}/tasklists', [TasklistController::class, 'index']);
        Route::get('/project/{project}/tasks', [ProjectTaskController::class, 'index']);
    });

});

Route::post('tmp-save-user', [RegistrationController::class, 'tmpSaveUser']);
Route::post('register-user', [RegistrationController::class, 'registerUser']);


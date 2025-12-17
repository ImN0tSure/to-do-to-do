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

    Route::get('/project', [ProjectController::class, 'index']);
    Route::post('/project', [ProjectController::class, 'store']);

    Route::group(['middleware' => 'project.participant'], function () {
        Route::get('/project/{project}/participants', [ProjectParticipantController::class, 'index']);
        Route::get('/project/{project}/tasklists', [TasklistController::class, 'index']);
        Route::get('/project/{project}/tasks', [ProjectTaskController::class, 'index']);
        Route::get('/project/{project}/tasks/{task}', [ProjectTaskController::class, 'show']);
        Route::put('/project/{project}/tasks/{task}', [ProjectTaskController::class, 'update']);
        Route::post('/project/{project}/tasks', [ProjectTaskController::class, 'store']);
    });

});

Route::post('tmp-save-user', [RegistrationController::class, 'tmpSaveUser']);
Route::post('register-user', [RegistrationController::class, 'registerUser']);


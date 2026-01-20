<?php

use App\Http\Controllers\Api\ProjectTaskController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\TasklistController;
use App\Http\Controllers\Api\UserInfoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserTaskController;
use App\Http\Controllers\Api\ProjectParticipantController;
use App\Http\Controllers\Api\InvitationController;
use App\Http\Controllers\Api\NotificationController;
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

    Route::get('/userinfo', [UserInfoController::class, 'show']);
    Route::put('/userinfo', [UserInfoController::class, 'update']);

    Route::get('/tasks-for-today', [UserTaskController::class, 'index']);

    Route::get('/project', [ProjectController::class, 'index']);
    Route::post('/project', [ProjectController::class, 'store']);

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);

    Route::group(['middleware' => 'project.participant'], function () {
        Route::get('/project/{project}/participants', [ProjectParticipantController::class, 'index']);
        Route::get('/project/{project}/participants/{participant}', [ProjectParticipantController::class, 'show']);
        Route::put('/project/{project}/participants/exclude', [ProjectParticipantController::class, 'excludeParticipants']);
        Route::get('/project/{project}/quit', [ProjectParticipantController::class, 'quitProject']);
        Route::get('/project/{project}/tasklists', [TasklistController::class, 'index']);
        Route::post('/project/{project}/tasklists', [TasklistController::class, 'store']);
        Route::put('/project/{project}/tasklists/{tasklist}', [TasklistController::class, 'update']);
        Route::delete('/project/{project}/tasklists/{tasklist}', [TasklistController::class, 'destroy']);
        Route::get('/project/{project}/tasks', [ProjectTaskController::class, 'index']);
        Route::get('/project/{project}/tasks/{task}', [ProjectTaskController::class, 'show']);
        Route::put('/project/{project}/tasks/{task}', [ProjectTaskController::class, 'update']);
        Route::delete('/project/{project}/tasks/{task}', [ProjectTaskController::class, 'destroy']);
        Route::post('/project/{project}/tasks', [ProjectTaskController::class, 'store']);
        Route::post('/project/{project}/invite-participant', [InvitationController::class, 'create']);
        Route::get('/project/{project}/role', [RoleController::class, 'index']);
    });

    Route::put('/invitation/accept', [InvitationController::class, 'accept']);
    Route::put('/invitation/decline', [InvitationController::class, 'decline']);
    Route::put('/invitation/response', [InvitationController::class, 'update']);

});

Route::post('tmp-save-user', [RegistrationController::class, 'tmpSaveUser']);
Route::post('register-user', [RegistrationController::class, 'registerUser']);


<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\HowMuchTime;
use App\Services\TasksForToday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTaskController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse {
        $user_id = Auth::id();

        $tasks = TasksForToday::getList($user_id);

        $data = [];

        foreach ($tasks as $task) {
            $data[] = [
                'id' => $task->id,
                'name' => $task->name,
                'inProgress' => $task->in_progress,
                'priority' => $task->priority,
                'projectUrl' => $task->project->url,
                'time' => HowMuchTime::expiresIn($task->end_date),
            ];
        }

        return response()->json([
            'success' => true,
            'tasks' => $data
        ]);
    }
}

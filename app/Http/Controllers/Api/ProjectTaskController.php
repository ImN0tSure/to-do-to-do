<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\HowMuchTime;
use Illuminate\Http\Request;

class ProjectTaskController extends Controller
{
    public function index(Request $request, $project_url) {
        $tasks = Project::where('url', $project_url)->first()->tasks()->get();

        $data = [];

        foreach ($tasks as $task) {
            $data[] = [
                'id' => $task->id,
                'name' => $task->name,
                'inProgress' => $task->in_progress,
                'priority' => $task->priority,
                'projectUrl' => $task->project->url,
                'time' => HowMuchTime::expiresIn($task->end_date),
                'tasklist_id' => $task->tasklist_id,
            ];
        }

        return response()->json([
            'success' => true,
            'tasks' => $data,
        ]);
    }
}

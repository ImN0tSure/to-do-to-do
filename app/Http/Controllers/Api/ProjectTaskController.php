<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Services\HowMuchTime;
use Illuminate\Http\Request;

class ProjectTaskController extends Controller
{
    public function index(Request $request, $project_url): \Illuminate\Http\JsonResponse
    {
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

    public function show(string $project_url, $task_id): \Illuminate\Http\JsonResponse
    {
        $task = Task::where('id', $task_id)
            ->whereHas('project', function ($query) use ($project_url) {
                $query->where('url', $project_url);
            })
            ->firstOrFail();

        $current_end_date = explode(" ", $task->end_date);

        $task['end_date'] = $current_end_date[0];
        $task['end_time'] = substr($current_end_date[1], 0, -3);

        return response()->json([
            'success' => true,
            'task' => $task,
        ]);
    }

    public function update(
        UpdateTaskRequest $request,
        string $project_url,
        string|int $task_id
    ): \Illuminate\Http\JsonResponse {
        $validate_data = $request->validated();

        if (isset($validate_data['end_date'])) {
            $validate_data['end_date'] .= ' ' . $validate_data['end_time'];
            unset($validate_data['end_time']);
        }

        $validate_data['in_progress'] = !!$validate_data['in_progress'];

        $task = Task::findOrFail($task_id);
        $task->update($validate_data);

        return response()->json([
            'success' => true,
            'message' => 'Задача обновлена.'
        ]);
    }
}

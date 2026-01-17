<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Services\GetProjectId;
use App\Services\HowMuchTime;
use App\Services\Task\CreateTaskService;
use App\Services\Task\DeleteTaskService;
use App\Services\Task\UpdateTaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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

    /* Вынести одинаковые действия в контроллерах в сервисы */
    public function store(
        StoreTaskRequest $request,
        $project_url,
        CreateTaskService $task_service
    ): \Illuminate\Http\JsonResponse {
        $project_id = GetProjectId::byUrl($project_url);
        Gate::authorize('task.create', $project_id);

        $task_service->execute($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Задача успешно создана.',
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
        string|int $task_id,
        UpdateTaskService $task_service
    ): \Illuminate\Http\JsonResponse {
        $project_id = GetProjectId::byUrl($project_url);
        $validate_data = $request->validated();

        $allowed = [];

        if (Gate::allows('task.update.tasklist', $project_id)) {
            $allowed[] = 'tasklist_id';
        }

        if (Gate::allows('task.update.executor.self', $project_id)) {
            if ($validate_data['executor_id'] == Auth::id() || $validate_data['executor_id'] == null) {
                $allowed[] = 'executor_id';
            }
        }

        if (Gate::allows('task.update.status', $project_id)) {
            $allowed[] = 'in_progress';
        }

        if (Gate::allows('task.update', $project_id)) {
            $allowed = array_keys($validate_data);
        }

        $task_service->execute(
            Arr::only($request->validated(), $allowed),
            $task_id
        );

        return response()->json([
            'success' => true,
            'message' => 'Задача обновлена.'
        ]);
    }

    public function destroy(Request $request, DeleteTaskService $task_service): \Illuminate\Http\JsonResponse
    {
        $project_url = $request->project;
        $project_id = GetProjectId::byUrl($project_url);

        Gate::authorize('task.delete', $project_id);

        $task_id = $request->task;
        $task_service->execute($task_id);

        return response()->json([
            'success' => true,
            'message' => 'Задача успешно удалена.'
        ]);
        /* Дописать удаление уведомлений */
    }
}

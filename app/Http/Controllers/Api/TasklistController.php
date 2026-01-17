<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTasklistRequest;
use App\Models\Task;
use App\Models\Tasklist;
use App\Services\GetProjectId;
use App\Services\Tasklist\CreateTasklistService;
use App\Services\Tasklist\DeleteTasklistService;
use App\Services\Tasklist\UpdateTasklistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TasklistController extends Controller
{
    public function index(Request $request, $project_url)
    {
        $project_id = GetProjectId::byUrl($project_url);

        $tasklists = Tasklist::where('project_id', $project_id)->select('id', 'name', 'project_id')->get();

        return response()->json([
            'success' => true,
            'tasklists' => $tasklists
        ]);
    }

    public function store(StoreTasklistRequest $request, $project_url, CreateTasklistService $tasklist_service)
    {
        $project_id = GetProjectId::byUrl($project_url);
        Gate::authorize('tasklist.create', [$project_id]);

        $tasklist = $tasklist_service->execute($request->validated(), $project_id);

        return response()->json([
            'success' => true,
            'tasklist' => [
                'id' => $tasklist->id,
                'name' => $tasklist->name,
                'project_id' => $tasklist->project_id,
            ]
        ]);
    }

    public function update(
        StoreTasklistRequest $request,
        $project_url,
        $tasklist_id,
        UpdateTasklistService $tasklist_service
    ): \Illuminate\Http\JsonResponse {
        $project_id = GetProjectId::byUrl($project_url);
        Gate::authorize('tasklist.update', $project_id);

        $tasklist_service->execute($request->validated(), $tasklist_id);

        return response()->json([
            'success' => true,
            'message' => 'Список задач переименован.'
        ]);
    }

    public function destroy($project_url, $tasklist_id, DeleteTasklistService $tasklist_service)
    {
        $project_id = GetProjectId::byUrl($project_url);
        Gate::authorize('tasklist.delete', [Tasklist::class, $project_id]);

        $tasklist_service->execute($tasklist_id);

        return response()->json([
            'success' => true,
            'message' => 'Список задач вместе с задачами успешно удалён.'
        ]);
    }
}

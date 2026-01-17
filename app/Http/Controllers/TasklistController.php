<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTasklistRequest;
use App\Http\Requests\UpdateTasklistRequest;
use App\Models\Tasklist;
use App\Services\GetProjectId;
use App\Services\Tasklist\CreateTasklistService;
use App\Services\Tasklist\DeleteTasklistService;
use App\Services\Tasklist\UpdateTasklistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TasklistController extends Controller
{
    protected $task_list_not_found = [
        'status' => 'error',
        'message' => 'Tasklist not found',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return 'index.page';
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $project_url)
    {
        $data = [
            'project_url' => $project_url,
        ];

        return view('tasklist.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTasklistRequest $request, $project_url, CreateTasklistService $tasklist_service)
    {
        $project_id = GetProjectId::byUrl($project_url);
        Gate::authorize('tasklist.create', [$project_id]);

        $tasklist = $tasklist_service->execute($request->validated(), $project_id);

        return response()
            ->json([
                'data' => [
                    'tasklist_id' => $tasklist->id
                ],
                'name' => $tasklist->name,
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $project_url, string $tasklist_id)
    {
        $tasklist = Tasklist::with('tasks')
            ->where('project_id', GetProjectId::byUrl($project_url))
            ->find($tasklist_id);

        if (!$this->findTasklist($tasklist)) {
            return $this->task_list_not_found;
        }

        return [
            'tasklist' => $tasklist,
        ];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $project_url, string $tasklist_id)
    {
        $tasklist = Tasklist::where('project_id', GetProjectId::byUrl($project_url))->find($tasklist_id);

        if (!$this->findTasklist($tasklist)) {
            return $this->task_list_not_found;
        }
        $data = [
            'project_url' => $project_url,
            'tasklist' => $tasklist,
        ];

        return view('tasklist.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateTasklistRequest $request,
        string $project_url,
        string $tasklist_id,
        UpdateTasklistService $tasklist_service
    ) {
        $project_id = GetProjectId::byUrl($project_url);
        Gate::authorize('tasklist.update', $project_id);

        $tasklist = $tasklist_service->execute($request->validated(), $tasklist_id);

        return response()
            ->json([
                'data' => [
                    'newName' => $tasklist->name,
                ],
                'status' => 'success',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $project_url, string $tasklist_id, DeleteTasklistService $tasklist_service)
    {
        $project_id = GetProjectId::byUrl($project_url);
        Gate::authorize('tasklist.delete', [Tasklist::class, $project_id]);

        $tasklist_service->execute($tasklist_id);

        return response()->json([
            'status' => 'success',
            'tasklist_id' => $tasklist_id,
            'message' => 'Tasklist successfully deleted',
        ]);
    }

    protected function findTasklist($tasklist): bool
    {
        if ($tasklist === null) {
            return false;
        } else {
            return true;
        }
    }

}

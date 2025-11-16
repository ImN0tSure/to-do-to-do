<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Models\UserInfo;
use App\Services\GetProjectId;
use App\Services\isStatusHigherThan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($project_url)
    {
        $project_id = getProjectId::byUrl($project_url);

        $this->authorize('create', [Task::class, $project_id]);

        $data = [
            'project' => Project::where('url', $project_url)
                ->with('tasklists')
                ->with('participants')
                ->first(),
        ];

        return view('task.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request, $project_url)
    {
        $project_id = GetProjectId::byUrl($project_url);

        $this->authorize('create', [Task::class, $project_id]);

        $validate_data = $request->validated();

        $validate_data['begin_date'] = date('Y-m-d H:i');
        $validate_data['end_date'] .= ' ' . $validate_data['end_time'];
        unset($validate_data['end_time']);

        Task::create($validate_data);

        return redirect()->route('project.show', $project_url);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $project_url, $task_id)
    {
        $project_id = getProjectId::byUrl($project_url);
        if (isStatusHigherThan::executor($project_id)) {
            return redirect()->route('task.edit', [$project_url, $task_id]);
        }

        $task = $this->getFullTaskInfo($project_url, $task_id);

        $tasklists = Project::where('url', $project_url)
            ->with('tasklists')
            ->first()
            ->tasklists;
        $current_user = UserInfo::where('user_id', Auth::id())->select('user_id', 'name', 'surname')->first();

        $data = [
            'task' => $task,
            'tasklists' => $tasklists,
            'project_url' => $project_url,
            'current_user' => $current_user
        ];

        return view('task.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $project_url, string|int $task_id)
    {
        $project_id = getProjectId::byUrl($project_url);
        if (!isStatusHigherThan::executor($project_id)) {
            return redirect()->route('task.show', [$project_url, $task_id]);
        }

        $task = $this->getFullTaskInfo($project_url, $task_id);

        $participants_and_tasklists = Project::where('url', $project_url)
            ->with('participants')
            ->with('tasklists')
            ->first();

        $data = [
            'task' => $task,
            'participants' => $participants_and_tasklists->participants,
            'tasklists' => $participants_and_tasklists->tasklists,
            'project_url' => $project_url,
        ];

        return view('task.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, string $project_url, string|int $task_id)
    {
        if (Gate::denies('update', [Task::class, $task_id])) {
            return redirect()->route('project.show', $project_url);
        }

        $validate_data = $request->validated();

        if (isset($validate_data['end_date'])) {
            $validate_data['end_date'] .= ' ' . $validate_data['end_time'];
            unset($validate_data['end_time']);
        }

        $task = Task::findOrFail($task_id);
        $task->update($validate_data);

        return redirect()->route('project.show', $project_url);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $project_url, string|int $task_id)
    {
        $project_id = GetProjectId::byUrl($project_url);
        $this->authorize('delete', [Task::class, $project_id]);

        Task::where('id', $task_id)->delete();
        return redirect()->route('project.show', $project_url);
    }

    protected function getFullTaskInfo(string $project_url, string|int $task_id)
    {
        return Project::where('url', $project_url)
            ->first()
            ->tasks()
            ->with('executor')
            ->with('tasklist')
            ->get()
            ->where('id', $task_id)
            ->first();
    }
}

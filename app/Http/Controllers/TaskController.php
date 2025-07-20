<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\Tasklist;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskNotFound = [
        'status' => 'error',
        'message' => 'Task not found',
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
    public function create($project_url)
    {
        $data = [
            'project' => Project::where('url', $project_url)->with('tasklists')->get(),
        ];

        return view('task.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $project_url)
    {
        $project_id = $this->getProjectId($project_url);
        $validateData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'tasklist_id' => 'integer|exists:tasklists,id',
            'priority' => 'integer|required|min:1|max:3',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        if ($this->tasklistBelongsToProject($project_id, $validateData['tasklist_id'])) {
            $validateData['project_id'] = $project_id;
            $validateData['begin_date'] = date('Y-m-d H:i');
            $validateData['end_date'] = $validateData['date'] . ' ' . $validateData['time'];
            unset($validateData['date']);
            unset($validateData['time']);

            Task::create($validateData);
            return redirect()->route('task.index', $project_url);
        } else {
            return redirect()->back()->withErrors(['tasklists' => 'The tasklist does not exist.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $project_url, $id)
    {
        $task = Project::where('url', $project_url)
            ->first()
            ->tasks()
            ->get()
            ->where('id', $id)
            ->first();

        if ($task === null) {
            return $this->taskNotFound;
        }

        return view('task.show', ['task' => $task]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $project_url, $task_id)
    {
        $task = Project::where('url', $project_url)
            ->first()
            ->tasks()
            ->get()
            ->where('id', $task_id)
            ->first();

        $tasklists = Project::where('url', $project_url)->first()->tasklists;

        if ($task === null) {
            return $this->taskNotFound;
        }

        $dateTime = explode(' ', $task->end_date);

        return view('task.edit', [
            'task' => $task,
            'tasklists' => $tasklists,
            'project_url' => $project_url,
            'date' => $dateTime[0],
            'time' => $dateTime[1],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $project_url, $task_id)
    {
        $project_id = $this->getProjectId($project_url);


        $validateData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'tasklist_id' => 'integer|exists:tasklists,id',
            'priority' => 'integer|required|min:1|max:3',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i:s',
            'in_progress' => 'boolean',
        ]);

        if ($this->tasklistBelongsToProject($project_id, $validateData['tasklist_id'])) {
            $validateData['end_date'] = $validateData['date'] . ' ' . $validateData['time'];
            unset($validateData['date']);
            unset($validateData['time']);
            Task::where('id', $task_id)
                ->update($validateData);
            return redirect()->route('task.index', $project_url);
        } else {
            return $this->taskNotFound;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $project_url, $task_id)
    {
        Task::destroy($task_id);
    }

    protected function getProjectId($project_url)
    {
        return Project::where('url', '=', $project_url)->value('id');
    }

    protected function tasklistBelongsToProject($project_id, $tasklist_id)
    {
        if (Tasklist::where('id', $tasklist_id)->where('project_id', $project_id)->exists()) {
            return true;
        } else {
            return false;
        }
    }
}

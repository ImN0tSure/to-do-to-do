<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\Tasklist;
use App\Services\GetProjectId;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    protected $task_not_found = [
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
    public function store(Request $request, $project_url)
    {
        $project_id = GetProjectId::byUrl($project_url);

        $validate_data = $request->validate([
            'name' => 'required|max:255|min:3',
            'description' => 'required|max:1500',
            'executor_id' => [
                'nullable',
                'integer',
                Rule::exists('project_participants', 'user_id')
                    ->where(function ($query) use ($project_id) {
                        $query->where('project_id', $project_id);
                    })
            ],
            'tasklist_id' => [
                'required',
                'integer',
                Rule::exists('tasklists', 'id')
                    ->where(function ($query) use ($project_id) {
                        $query->where('project_id', $project_id);
                    }),
            ],
            'end_date' => 'required|date',
            'end_time' => 'required|date_format:H:i',
            'priority' => 'integer|required|min:1|max:3',
        ]);

        $validate_data['begin_date'] = date('Y-m-d H:i');
        $validate_data['end_date'] .= ' ' . $validate_data['end_time'];
        unset($validate_data['end_time']);

        Task::create($validate_data);

        return redirect()->route('project.show', $project_url);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $project_url, $id)
    {
        $task = Project::where('url', $project_url)
            ->first()
            ->tasks()
            ->with('executor')
            ->with('tasklist')
            ->get()
            ->where('id', $id)
            ->first();

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

        return view('task.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $project_url, $task_id)
    {
//        $task = Project::where('url', $project_url)
//            ->first()
//            ->tasks()
//            ->get()
//            ->where('id', $task_id)
//            ->first();
//
//        $tasklists = Project::where('url', $project_url)->first()->tasklists;
//
//        if ($task === null) {
//            return $this->task_not_found;
//        }
//
//        $dateTime = explode(' ', $task->end_date);
//
//        return view('task.edit', [
//            'task' => $task,
//            'tasklists' => $tasklists,
//            'project_url' => $project_url,
//            'date' => $dateTime[0],
//            'time' => $dateTime[1],
//        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $project_url, $task_id)
    {
        $project_id = GetProjectId::byUrl($project_url);

        $validate_data = $request->validate([
            'name' => 'required|max:255|min:3',
            'description' => 'required|max:1500',
            'executor_id' => [
                'nullable',
                'integer',
                Rule::exists('project_participants', 'user_id')
                    ->where(function ($query) use ($project_id) {
                        $query->where('project_id', $project_id);
                    })
            ],
            'tasklist_id' => [
                'required',
                'integer',
                Rule::exists('tasklists', 'id')
                    ->where(function ($query) use ($project_id) {
                        $query->where('project_id', $project_id);
                    }),
            ],
            'end_date' => 'required|date',
            'end_time' => 'required|date_format:H:i',
            'priority' => 'integer|required|min:1|max:3',
            'in_progress' => 'boolean',
        ]);

        $validate_data['end_date'] .= ' ' . $validate_data['end_time'];
        unset($validate_data['end_time']);

        Task::where('id', $task_id)
            ->update($validate_data);

        return redirect()->route('project.show', $project_url);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $project_url, $task_id)
    {
        Task::where('id', $task_id)->delete();
        return redirect()->route('project.show', $project_url);
    }
}

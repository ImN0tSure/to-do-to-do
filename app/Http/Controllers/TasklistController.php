<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Tasklist;
use App\Services\GetProjectId;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
    public function store(Request $request, $project_url)
    {
        $validate_data = $request->validateWithBag('tasklist', [
            'name' => 'required|max:255|min:3',
            'description' => 'max:255',
        ]);

        $project_id = GetProjectId::byUrl($project_url);
        $validate_data['project_id'] = $project_id;

        $tasklist = Tasklist::create($validate_data);

        return response()
            ->json([
                'data' => [
                    'tasklist_id' => $tasklist->id
                ],
                'name' => $validate_data['name'],
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
    public function update(Request $request, string $project_url, string $tasklist_id)
    {
        $validate_data = $request->validateWithBag('tasklist_update', [
            'name' => 'required|max:255|min:3',
            'oldName' => [
                'required',
                'max:255',
                'min:3',
                Rule::exists('tasklists', 'name')
                    ->where('id', $tasklist_id)
            ],
        ]);
        unset($validate_data['oldName']);
        Tasklist::where('id', $tasklist_id)
            ->update($validate_data);

        return response()
            ->json([
                'data' => [
                    'newName' => $validate_data['name'],
                ],
                'status' => 'success',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $project_url, string $tasklist_id)
    {
        $tasklist = Tasklist::where('project_id', GetProjectId::byUrl($project_url))
            ->where('id', $tasklist_id)
            ->with(['tasks.taskParticipantRecord'])
            ->delete();

        return response()->json([
            'status' => 'success',
            'tasklist_id' => $tasklist_id,
            'tasklist' => $tasklist,
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

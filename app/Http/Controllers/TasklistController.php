<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Tasklist;
use Illuminate\Http\Request;

class TasklistController extends Controller
{
    protected $tasklistNotFound = [
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
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'max:255',
        ]);

        $project_id = $this->getProjectId($project_url);
        $validatedData['project_id'] = $project_id;

        Tasklist::create($validatedData);
        return redirect(route('tasklist.index', $project_url));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $project_url, string $tasklist_id)
    {
        $tasklist = Tasklist::with('tasks')
            ->where('project_id', $this->getProjectId($project_url))
            ->find($tasklist_id);

        if (!$this->findTasklist($tasklist)) {
            return $this->tasklistNotFound;
        }

        $response = [
            'tasklist' => $tasklist,
        ];

        return $response;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $project_url, string $tasklist_id)
    {
        $tasklist = Tasklist::where('project_id', $this->getProjectId($project_url))->find($tasklist_id);

        if (!$this->findTasklist($tasklist)) {
            return $this->tasklistNotFound;
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

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'max:255',
        ]);

        Tasklist::where('id', $tasklist_id)
            ->where('project_id', $this->getProjectId($project_url))
            ->update($validatedData);

        redirect(route('tasklist.index', $project_url));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $project_url, string $tasklist_id)
    {
        Tasklist::where('project_id', $this->getProjectId($project_url))->destroy($tasklist_id);
        return redirect(route('tasklist.index', $project_url));
    }

    protected function getProjectId($project_url)
    {
        return Project::where('url', '=', $project_url)->value('id');
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

<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Project;
use App\Models\ProjectParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public static function index(): \Illuminate\Support\Collection
    {
        $user_id = Auth::id();

        $projects_id = ProjectParticipant::where('user_id', $user_id)
            ->pluck('project_id')
            ->toArray();

        return Project::getProjectsList($projects_id);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('project.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validData = $request->validate([
            'name' => 'required',
            'description' => 'required | max:255',
            'end_date' => 'max:255',
        ]);

        do {
            $validData['url'] = Str::random(10);
        } while (Project::where('url', $validData['url'])->first() != null);

        $validData['begin_date'] = date('Y-m-d H:i:s');

        $participate = [
            'user_id' => Auth::id(), // Поменять на подтягивающийся из сессии
            'project_id' => Project::createProject($validData),
            'status' => 1,
        ];

        ProjectParticipant::create($participate);
        return redirect()->route('cabinet');
    }

    /**
     * Display the specified resource.
     */
    public function show($project_url): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $project = Project::where('url', $project_url)->first();

        if (!$project) {
            return abort(404);
        }

        $data = [
            'projects' => $this->index(),
            'current_project' => $project,
            'participants' => $project->participants()->select('name', 'surname', 'avatar_img')->get(),
            'tasklists' => $project->tasklists()->with('tasks')->get(),
        ];

        return view('project.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $url): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $data = [
            'project' => Project::where('url', $url)->first()
        ];

        return view('project.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $url): \Illuminate\Http\RedirectResponse
    {
        $validData = $request->validate([
            'name' => 'required',
            'description' => 'required | max:255',
        ]);

        Project::where('url', $url)->update($validData);
        return redirect()->route('project.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url): \Illuminate\Http\RedirectResponse
    {
        Project::where('url', $url)->delete();
        return redirect()->route('index');
    }

    public function quit(string $url): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $user_id = Auth::id();
        $project = Project::where('url', $url)->first();
        $task_id = $project->tasks()
            ->where('executor_id', $user_id)
            ->pluck('tasks.id')
            ->toArray();

        try {
            DB::transaction(function () use ($project, $user_id, $task_id) {
                Notification::whereIn('notifiable_id', $task_id)
                    ->where([
                        'notifiable_type' => 'task_deadline',
                        'deleted_at' => null
                    ])
                    ->update(['deleted_at' => now()]);

                $project->tasks()->where('executor_id', $user_id)->update(['executor_id' => null]);

                $project->participantRecords()->where('user_id', $user_id)->delete();
            });

            return redirect('cabinet');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExcludeParticipantsRequest;
use App\Http\Requests\SaveProjectRequest;
use App\Models\Notification;
use App\Models\Project;
use App\Models\ProjectParticipant;
use App\Services\GetParticipantStatus;
use App\Services\GetProjectId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
    public function store(SaveProjectRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validate_data = $request->validated();

        do {
            $validate_data['url'] = Str::random(10);
        } while (Project::where('url', $validate_data['url'])->first() != null);

        $validate_data['begin_date'] = date('Y-m-d H:i:s');

        $participate = [
            'user_id' => Auth::id(),
            'project_id' => Project::createProject($validate_data),
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
        $project_id = GetProjectId::byUrl($project_url);

//        $project = Project::where('url', $project_url)->first();
        $project = Cache::remember("project:{$project_id}", 600, function () use ($project_id) {
            return Project::where('id', $project_id)->first();
        });

//        $participants = $project
//            ->participants()
//            ->select('name', 'surname', 'avatar_img', 'user_infos.user_id')
//            ->get();

        $participants = Cache::remember("project:{$project_id}:participants", 600, function () use ($project_id) {
            return Project::where('id', $project_id)
                ->first()
                ->participants()
                ->select('name', 'surname', 'avatar_img', 'user_infos.user_id')
                ->get();
        });


        $tasklists = Cache::remember("project:{$project_id}:tasklists", 60, function () use ($project_id) {
            return Project::where('id', $project_id)
                ->first()
                ->tasklists()
                ->with('tasks')
                ->get();
        });

        $data = [
            'projects' => $this->index(),
            'current_project' => $project,
            'participants' => $participants,
            'current_user_status' => $participants->where('user_id', Auth::id())->first()->pivot->status,
            'tasklists' => $tasklists,
        ];

        return view('project.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $url): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        abort('404');
//        $data = [
//            'project' => Project::where('url', $url)->first()
//        ];
//
//        return view('project.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveProjectRequest $request, string $url): \Illuminate\Http\RedirectResponse
    {
        $validate_data = $request->validated();

        Project::where('url', $url)->update($validate_data);
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

    public function quit(string $project_url): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $user_id = Auth::id();

        $response = $this->removeProjectConnectedData($project_url, $user_id);

        if ($response) {
            return redirect()->route('cabinet');
        } else {
            return redirect()->back()->withErrors([
                'status' => 'error',
                'message' => $response,
            ]);
        }
    }

    public function excludeParticipants(ExcludeParticipantsRequest $request, $project_url) {

        $validate_data = $request->validated();

        $ids = $validate_data['ids'];

        $response_data = [];

        foreach($ids as $user_id) {
            $response = $this->removeProjectConnectedData($project_url, $user_id);
            if ($response) {
                $response_data[$user_id] = [
                    'status' => 'success',
                ];
            } else {
                $response_data[$user_id] = [
                    'status' => 'error',
                    'message' => $response,
                ];
            }
        }

        return response()->json($response_data);
    }

    public function removeProjectConnectedData ($project_url, $user_id) {
        $project = Project::where('url', $project_url)->first();
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
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return true;
    }
}

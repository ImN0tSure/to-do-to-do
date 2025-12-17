<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveProjectRequest;
use App\Models\Project;
use App\Models\ProjectParticipant;
use App\Services\GenerateProjectUrl;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse {
        $user_id = Auth::id();

        $project_ids = ProjectParticipant::where('user_id', $user_id)
            ->pluck('project_id')
            ->toArray();

        $projects = Project::whereIn('id', $project_ids)->select('url', 'name', 'description')->get();
        return response()->json([
            'success' => true,
            'projects' => $projects
        ]);
    }

    public function store(SaveProjectRequest $request) {

        $validate_data = $request->validated();

        $validate_data['url'] = GenerateProjectUrl::generate();
        $validate_data['begin_date'] = date('Y-m-d H:i:s');

        $new_project = Project::create($validate_data);

        $participate = [
            'user_id' => Auth::id(),
            'project_id' => $new_project->id,
            'status' => 1
        ];

        ProjectParticipant::create($participate);

        return response()->json([
            'success' => true,
            'message' => 'Проект создан.'
        ]);
    }
}

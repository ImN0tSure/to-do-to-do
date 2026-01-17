<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveProjectRequest;
use App\Models\Project;
use App\Models\ProjectParticipant;
use App\Services\Project\CreateProjectService;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
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

    public function store(
        SaveProjectRequest $request,
        CreateProjectService $project_service
    ): \Illuminate\Http\JsonResponse {
        $validate_data = $request->validated();

        $project_service->execute($validate_data);

        return response()->json([
            'success' => true,
            'message' => 'Проект создан.'
        ]);
    }
}

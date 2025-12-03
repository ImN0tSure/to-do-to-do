<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse {
        $user_id = Auth::id();

        $project_ids = ProjectParticipant::where('user_id', $user_id)
            ->pluck('project_id')
            ->toArray();

        $projects = Project::whereIn('id', $project_ids)->select('url', 'name')->get();
        return response()->json([
            'success' => true,
            'projects' => $projects
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectParticipant;
use App\Services\GetProjectId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProjectParticipantController extends Controller
{
    public function index(Request $request, string $project_url): \Illuminate\Http\JsonResponse
    {
        $project_id = GetProjectId::byUrl($project_url);

        $participants = Cache::remember("project:{$project_id}:participants", 600, function () use ($project_id) {
            return Project::where('id', $project_id)
                ->first()
                ->participants()
                ->select('name', 'surname', 'avatar_img', 'user_infos.user_id')
                ->get();
        });

        return response()->json([
            'success' => true,
            'participants' => $participants
        ]);
    }
}

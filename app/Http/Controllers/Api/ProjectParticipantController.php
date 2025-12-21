<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectParticipant;
use App\Models\UserInfo;
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

    public function show(Request $request, string $project_url, string $participant_id): \Illuminate\Http\JsonResponse
    {
        $project_id = GetProjectId::byUrl($project_url);

        if (ProjectParticipant::where('project_id', $project_id)->where('user_id', $participant_id)->exists()) {
            $participant_data = UserInfo::where('user_id', $participant_id)
                ->select('name', 'surname', 'patronymic', 'avatar_img', 'about', 'phone', 'contact_email')
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'participant' => $participant_data
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Пользователь не состоит в проекте.'
            ]);
        }
    }
}

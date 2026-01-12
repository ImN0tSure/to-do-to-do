<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExcludeParticipantsRequest;
use App\Models\Notification;
use App\Models\Project;
use App\Models\ProjectParticipant;
use App\Models\UserInfo;
use App\Services\GetProjectId;
use App\Services\RemoveUserProjectData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ProjectParticipantController extends Controller
{
    public function index(Request $request, string $project_url): \Illuminate\Http\JsonResponse
    {
        $project_id = GetProjectId::byUrl($project_url);

        $participants = Cache::remember("project:{$project_url}:participants", 60, function () use ($project_id) {
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

    public function quitProject(Request $request, string $project_url): \Illuminate\Http\JsonResponse {
        $user_id = Auth::id();

        $response = RemoveUserProjectData::remove($project_url, $user_id);

        if ($response) {
            return response()->json([
                'success' => true,
                'message' => 'Вы успешно покинули проект.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $response
            ]);
        }
    }

    public function excludeParticipants(ExcludeParticipantsRequest $request, string $project_url): \Illuminate\Http\JsonResponse
    {
        $project_id = GetProjectId::byUrl($project_url);
        Gate::authorize('project.participant.exclude', $project_id);

        $validate_data = $request->validated();

        $ids = $validate_data['ids'];

        $response_data = [];

        foreach ($ids as $user_id) {
            $response = RemoveUserProjectData::remove($project_url, $user_id);

            if ($response) {
                $response_data[$user_id] = [
                    'success' => true,
                ];
            } else {
                $response_data[$user_id] = [
                    'success' => false,
                    'message' => $response,
                ];
            }
        }

        Cache::delete("project:{$project_url}:participants");

        return response()->json([
            'success' => true,
            'response_data' => $response_data
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProjectParticipant;
use App\Services\GetProjectId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $project_id = GetProjectId::byUrl($request->project);

        $participant = ProjectParticipant::where([
            'project_id' => $project_id,
            'user_id' => Auth::id()
        ])->firstOrFail();

        $role = $participant->role;
        $permissions = config('project_permissions');

        return response()->json([
            'success' => true,
            'role' => $role,
            'permissions' => $permissions[$role],
        ]);
    }
}

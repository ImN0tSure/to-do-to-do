<?php

namespace App\Services\Permissions;

use App\Models\ProjectParticipant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProjectPermissionResolver
{
    public function check(User $user, string $permission, $project_id)
    {
        $project_participant = ProjectParticipant::where([
            'project_id' => $project_id,
            'user_id' => Auth::id(),
        ])->first();

        $role = $project_participant->role;

        $permissions = config('project_permissions');

        if ($role === 'creator') {
            return true;
        } elseif (in_array($permission, $permissions[$role])) {
            return true;
        } else {
            return false;
        }
    }
}

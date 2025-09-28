<?php

namespace App\Services;

use App\Models\ProjectParticipant;
use Illuminate\Support\Facades\Auth;

class GetParticipantStatus
{
    public static function inProject($project_id) {
        return ProjectParticipant::where([
            'project_id' => $project_id,
            'user_id' => Auth::id()
        ])->value('status');
    }
}

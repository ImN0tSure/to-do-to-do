<?php

namespace App\Services;

use App\Models\Project;

class CheckParticipant
{
    public static function project($project_url, $user_id)
    {
        $project = Project::where('url', $project_url)->first();

        if (!$project) {
            return false;
        }

        return $project->participantRecords()->where('user_id', $user_id)->exists();
    }

}

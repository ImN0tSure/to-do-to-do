<?php

namespace App\Services;

use App\Models\Project;

class CheckParticipant
{
    public static function project($project_url, $user_id)
    {
        $data = Project::where('url', $project_url)->first();

        if (!$data) {
            return false;
        }

        return $data->participantRecords()->where('user_id', $user_id)->exists();
    }

    public static function task($user_id, $task_id)
    {
    }

}

<?php

namespace App\Services;

use App\Models\Project;

class GetProjectId
{
    public static function byUrl($project_url): string
    {
        $project = Project::where('url', '=', $project_url)->first();

        if (!$project) {
            return false;
        }

        return $project->id;
    }
}

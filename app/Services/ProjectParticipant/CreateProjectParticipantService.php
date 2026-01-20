<?php

namespace App\Services\ProjectParticipant;

use App\Models\ProjectParticipant;

class CreateProjectParticipantService
{
    public function execute(int $project_id, int $participant_id, string $status, string $role)
    {
        return ProjectParticipant::create([
            'project_id' => $project_id,
            'user_id' => $participant_id,
            'status' => $status,
            'role' => $role
        ]);
    }
}

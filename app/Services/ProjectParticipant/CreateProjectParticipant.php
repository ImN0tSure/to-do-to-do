<?php

namespace App\Services\ProjectParticipant;

use App\Models\ProjectParticipant;

class CreateProjectParticipant
{
    public function execute(array $data) {
        return ProjectParticipant::create($data);
    }
}

<?php

namespace App\Services;

class isStatusHigherThan
{
    protected static array $statuses = [
        'creator' => 0,
        'curator' => 1,
        'executor' =>2
    ];
    public static function executor(int|string $project_id): bool {
        $user_status = getParticipantStatus::inProject($project_id);

        return $user_status < self::$statuses['executor'];
    }

    public static function curator(int|string $project_id): bool {
        $user_status = getParticipantStatus::inProject($project_id);

        return $user_status < self::$statuses['curator'];
    }
}

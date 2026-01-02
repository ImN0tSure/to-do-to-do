<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class RemoveUserProjectData
{
    public static function remove($project_url, $user_id) {

        $project = Project::where('url', $project_url)->first();
        $task_id = $project->tasks()
            ->where('executor_id', $user_id)
            ->pluck('tasks.id')
            ->toArray();

        try {
            DB::transaction(function () use ($project, $user_id, $task_id) {
                Notification::whereIn('notifiable_id', $task_id)
                    ->where([
                        'notifiable_type' => 'task_deadline',
                        'deleted_at' => null
                    ])
                    ->update(['deleted_at' => now()]);

                $project->tasks()->where('executor_id', $user_id)->update(['executor_id' => null]);

                $project->participantRecords()->where('user_id', $user_id)->delete();
            });
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return true;
    }
}

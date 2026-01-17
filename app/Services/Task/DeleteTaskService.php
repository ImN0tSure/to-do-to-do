<?php

namespace App\Services\Task;

use App\Models\Task;

class DeleteTaskService
{
    public function execute($task_id) {
        $task_record = Task::where('id', $task_id)->firstOrFail();
        return $task_record->delete();
    }
}

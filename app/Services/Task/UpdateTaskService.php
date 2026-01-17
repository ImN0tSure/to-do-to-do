<?php

namespace App\Services\Task;

use App\Models\Task;

class UpdateTaskService
{
    public function execute(array $data, $task_id)
    {
        $task = Task::findOrFail($task_id);
        return $task->update($data);
    }
}

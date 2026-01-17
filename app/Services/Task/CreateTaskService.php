<?php

namespace App\Services\Task;

use App\Models\Task;

class CreateTaskService
{
    public function execute(array $data): Task
    {
        $data['begin_date'] = date('Y-m-d H:i');
        $data['end_date'] .= ' ' . $data['end_time'];
        unset($data['end_time']);

        return Task::create($data);
    }
}

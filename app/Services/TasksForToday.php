<?php

namespace App\Services;

use App\Models\Task;

class TasksForToday
{
    public static function getList($user_id)
    {
        return Task::where('executor_id', $user_id)->with(['project:url'])->get();
    }
}

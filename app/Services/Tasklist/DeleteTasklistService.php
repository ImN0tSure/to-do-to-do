<?php

namespace App\Services\Tasklist;

use App\Models\Task;
use App\Models\Tasklist;
use Illuminate\Support\Facades\DB;

class DeleteTasklistService
{
    public function execute($tasklist_id)
    {
        return DB::transaction(function () use ($tasklist_id) {
            Tasklist::destroy($tasklist_id);

            Task::where('tasklist_id', $tasklist_id)->each(function ($task_record) {
                $task_record->delete();
            });
        });
    }
}

<?php

namespace App\Services\Tasklist;

use App\Models\Tasklist;

class UpdateTasklistService
{
    public function execute($data, $tasklist_id)
    {
        unset ($data['oldName']);

        $tasklist = Tasklist::findOrFail($tasklist_id);

        return $tasklist->update($data);
    }
}

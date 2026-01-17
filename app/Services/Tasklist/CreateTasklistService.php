<?php

namespace App\Services\Tasklist;

use App\Models\Tasklist;

class CreateTasklistService
{
    public function execute($data, $project_id): Tasklist
    {
        $data['project_id'] = $project_id;

        return Tasklist::create($data);
    }
}

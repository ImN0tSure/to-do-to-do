<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Projects extends Model
{
    use HasFactory;

    public static function getProjectsList($projects_id): \Illuminate\Support\Collection
    {
        return DB::table("projects")
            ->whereIn("id", $projects_id)
            ->orderBy("id", 'asc')
            ->get();
    }

    public static function createProject($data): int
    {
       return DB::table("projects")->insertGetId($data);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tasks extends Model
{
    use HasFactory;

    public static function forToday(array $tasks_id): \Illuminate\Support\Collection
    {
        return DB::table("tasks")
            ->whereIn("id", $tasks_id)
            ->orderBy("id", 'asc')
            ->get();
    }
}

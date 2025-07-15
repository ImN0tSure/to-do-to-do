<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Project extends Model
{
    use HasFactory;

    public static function getProjectsList($projects_id): Collection
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

    public function projectParticipants(): HasMany {
        return $this->hasMany(ProjectParticipant::class);
    }

    public function lists(): HasMany {
        return $this->hasMany(Lists::class);
    }
}

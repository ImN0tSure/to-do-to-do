<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'url', 'begin_date', 'end_date'];
    public $timestamps = false;

    public function getRouteKeyName()
    {
        return 'url';
    }

    public static function getProjectsList($projects_id): Collection
    {
        return DB::table("projects")
            ->whereIn("id", $projects_id)
            ->select("id", "url", "name", "description")
            ->orderBy("id", 'asc')
            ->get();
    }

    public static function createProject($data): int
    {
        return DB::table("projects")->insertGetId($data);
    }

    public function projectParticipants(): HasMany
    {
        return $this->hasMany(ProjectParticipant::class);
    }

    public function tasklists(): HasMany
    {
        return $this->hasMany(Tasklist::class);
    }

    public function tasks(): HasManyThrough
    {
        return $this->hasManyThrough(Task::class, Tasklist::class);
    }

    public function participantRecords(): HasMany
    {
        return $this->hasMany(ProjectParticipant::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->BelongsToMany(
            UserInfo::class,
            'project_participants',
            'project_id',
            'user_id'
        )->withPivot('status');
    }


}

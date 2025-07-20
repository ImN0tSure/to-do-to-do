<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'begin_date', 'end_date', 'priority', 'tasklist_id'];
    public $timestamps = false;

    public static function forToday(array $tasks_id): Collection
    {
        return DB::table("tasks")
            ->whereIn("id", $tasks_id)
            ->orderBy("id", 'asc')
            ->get();
    }

    public function taskParticipants(): HasMany
    {
        return $this->hasMany(TaskParticipant::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function tasklist(): BelongsTo
    {
        return $this->belongsTo(Tasklist::class);
    }
}

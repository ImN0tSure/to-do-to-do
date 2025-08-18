<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'begin_date', 'end_date', 'priority', 'tasklist_id'];
    public $timestamps = false;

    public static function forToday(
        array $tasks_id,
        string $order_by = 'id',
        string $order_direction = 'asc'
    ): Collection {
        return DB::table("tasks")
            ->whereIn("id", $tasks_id)
            ->orderBy($order_by, $order_direction)
            ->get();
    }

    public function executor(): HasOneThrough
    {
        return $this->hasOneThrough(
            UserInfo::class,
            TaskParticipant::class,
            'task_id',
            'user_id',
            'id',
            'user_id'
        );
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function tasklist(): BelongsTo
    {
        return $this->belongsTo(Tasklist::class);
    }

    public function project(): HasOneThrough
    {
        return $this->hasOneThrough(
            Project::class,
            Tasklist::class,
            'id',
            'id',
            'tasklist_id',
            'project_id'
        );
    }
}

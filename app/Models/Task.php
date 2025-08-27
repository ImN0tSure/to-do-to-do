<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'begin_date', 'end_date', 'priority', 'tasklist_id'];
    public $timestamps = false;

    public function taskParticipantRecord(): HasOne
    {
        return $this->hasOne(TaskParticipant::class);
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

    public function notifications(): morphMany
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    public function createDeadlineNotification(string $type)
    {
        if ($this->executor) {
            Notification::create([
                'user_id' => $this->executor->user_id,
                'notifiable_type' => 'task_deadline',
                'notifiable_id' => $this->id,
                'type' => $type,
            ]);
        } else {
            $curators = $this->project->participantRecords()->where('status', 1)->get();

            $notifications = $curators->map(function ($curator) use ($type) {
                return [
                    'user_id' => $curator->user_id,
                    'notifiable_type' => 'task_deadline',
                    'notifiable_id' => $this->id,
                    'type' => $type,
                ];
            })->toArray();

            Notification::insert($notifications);
        }
    }
}

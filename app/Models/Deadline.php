<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Deadline extends Model
{
    use HasFactory;

    protected $fillable = ['task_id'];

    public function task(): HasOne {
        return $this->hasOne(Task::class);
    }

    public function notifications(): morphMany
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
}

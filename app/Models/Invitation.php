<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'inviter_id',
        'invitee_id',
        'project_id',
        'is_accepted',
    ];

    public $timestamps = false;

    public function inviter(): BelongsTo
    {
        return $this
            ->belongsTo(UserInfo::class, 'inviter_id', 'user_id')
            ->select('user_id', 'name', 'surname');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class)->select('id', 'name');
    }

    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
}

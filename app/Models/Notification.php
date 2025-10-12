<?php

namespace App\Models;

use App\Mail\DeadlineEmail;
use App\Mail\InvitationEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Mail\Mailable;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'notifiable_type',
        'notifiable_id',
        'event',
        'event_type',
        'read_at',
        'deleted_at',
    ];
    public $timestamps = false;

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function userInfo(): BelongsTo
    {
        return $this->belongsTo(UserInfo::class, 'user_id', 'user_id');
    }

    public function getMailable(): Mailable {
        return match($this->event) {
            'deadline' => new DeadlineEmail($this),
            'invitation' => new InvitationEmail($this),
        };
    }

}

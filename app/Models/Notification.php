<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
}

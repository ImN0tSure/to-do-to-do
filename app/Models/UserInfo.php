<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserInfo extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(Users::class);
    }
}

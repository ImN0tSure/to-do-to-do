<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Users extends Model
{
    use HasFactory;

    public function userInfo(): HasOne
    {
        return $this->hasOne(UserInfo::class);
    }

    public function taskParticipants(): HasMany {
        return $this->hasMany(TaskParticipant::class);
    }

    public function comments(): HasMany {
        return $this->hasMany(Comment::class);
    }

    public function projectParticipants(): HasMany {
        return $this->hasMany(ProjectParticipant::class);
    }

}

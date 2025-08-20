<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['email', 'password'];
    public $timestamps = false;

    public function userInfo(): HasOne
    {
        return $this->hasOne(UserInfo::class);
    }

    public function taskParticipants(): HasMany
    {
        return $this->hasMany(TaskParticipant::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function projectParticipants(): HasMany
    {
        return $this->hasMany(ProjectParticipant::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserInfo extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'surname', 'name', 'patronymic', 'avatar_img', 'nickname', 'phone'];
    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

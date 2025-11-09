<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'surname',
        'name',
        'patronymic',
        'avatar_img',
        'phone',
        'contact_email',
        'about'
    ];
    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(
            ProjectParticipant::class,
            'projects',
            'user_id',
            'project_id'
        )->withPivot('status');
    }
}

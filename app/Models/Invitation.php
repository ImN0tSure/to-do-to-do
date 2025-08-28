<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}

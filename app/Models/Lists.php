<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lists extends Model
{
    use HasFactory;

    public function tasks():HasMany {
        return $this->hasMany(Task::class);
    }

    public function project():BelongsTo {
        return $this->belongsTo(Project::class);
    }
}

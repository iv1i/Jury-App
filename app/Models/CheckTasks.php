<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckTasks extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sumary',
        'easy',
        'medium',
        'hard',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Teams::class);
    }
}

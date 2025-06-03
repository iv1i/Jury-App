<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'complexity',
        'price',
        'description',
        'solved',
        'flag',
    ];

    protected $hidden = [
        'flag',
        'FILES',
    ];

    public function SolvedTasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SolvedTasks::class);
    }
    public function desidedtasksteams(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(desided_tasks_teams::class);
    }
}

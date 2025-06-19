<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        'web_port',
        'db_port',
        'web_directory',
        'FILES',
    ];



    protected $hidden = [
        'flag',
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

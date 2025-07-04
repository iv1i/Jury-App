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

    public function complexityRelashion(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Complexities::class, 'complexity', 'id');
    }
    public function categoryRelashion(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Categories::class, 'category', 'id');
    }
    public function solvedTasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SolvedTasks::class);
    }
    public function  completed_task_team(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CompletedTaskTeams::class);
    }
}

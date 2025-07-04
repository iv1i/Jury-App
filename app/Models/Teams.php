<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Teams extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'password',
        'players',
        'wherefrom',
        'guest',
        'scores',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'token',
        'remember_token',
    ];

    public function checkTasks(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CheckTasks::class);
    }
    public function solvedTasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SolvedTasks::class);
    }
    public function completed_task_team(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CompletedTaskTeams::class);
    }
}

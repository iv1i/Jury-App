<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
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
        'password_encr',
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
    public function desidedtasksteams(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(desided_tasks_teams::class);
    }
}

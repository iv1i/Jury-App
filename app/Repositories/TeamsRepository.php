<?php

namespace App\Repositories;

use App\Models\Teams;
use Illuminate\Support\Facades\Cache;

class TeamsRepository
{
    public function getAllTeams()
    {
        $Teams = Cache::get('All-Teams');

        if (is_null($Teams)) {
            $Teams = Teams::all();
            Cache::tags('ModelList')->put('All-Teams', $Teams, now()->addMinutes(10));
        }
        return $Teams;
    }

    public function getAllTeamsNoHidden()
    {
        $Teams = Cache::get('All-NoHidden-Teams');

        if (is_null($Teams)) {
            $Teams = Teams::all()->makeVisible(['password', 'token', 'remember_token']);
            Cache::tags('ModelList')->put('All-NoHidden-Teams', $Teams, now()->addMinutes(10));
        }
        return $Teams;
    }
}

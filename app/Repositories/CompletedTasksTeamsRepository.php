<?php

namespace App\Repositories;

use App\Models\CompletedTaskTeams;
use Illuminate\Support\Facades\Cache;

class CompletedTasksTeamsRepository
{
    public function getAllCompletedTasksAndTeams()
    {
        $Desided = Cache::get('All-Desided');

        if (is_null($Desided)) {
            $Desided = CompletedTaskTeams::all();
            Cache::tags('ModelList')->put('All-Desided', $Desided, now()->addMinutes(10));
        }
        return $Desided;
    }

}

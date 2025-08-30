<?php

namespace App\Repositories;

use App\Models\CompletedTaskTeams;
use App\Models\SolvedTasks;
use Illuminate\Support\Facades\Cache;

class CompletedTasksTeamsRepository
{
    public function getAllSolvedTasks()
    {
        $solvedTasks = Cache::get('All-Solved');

        if (is_null($solvedTasks)) {
            $solvedTasks = SolvedTasks::all();
            Cache::tags('ModelList')->put('All-Solved', $solvedTasks, now()->addMinutes(10));
        }
        return $solvedTasks;
    }

}

<?php

namespace App\Repositories;

use App\Models\Tasks;
use Illuminate\Support\Facades\Cache;

class TaskRepository
{
    public function getAllTasks()
    {
        $tasks = Cache::get('All-Tasks');

        if (is_null($tasks)) {
            $tasks = Tasks::all();

            Cache::tags('ModelList')->put('All-Tasks', $tasks, now()->addMinutes(10));
        }

        return $tasks;
    }

    public function getAllTasksNoHidden()
    {
        $Tasks = Cache::get('All-NoHidden-Tasks');

        if (is_null($Tasks)) {
            $Tasks = Tasks::all()->makeVisible('flag');
            Cache::tags('ModelList')->put('All-NoHidden-Tasks', $Tasks, now()->addMinutes(10));
        }
        return $Tasks;
    }
}

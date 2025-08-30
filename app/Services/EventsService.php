<?php

namespace App\Services;

use App\Events\AdminHomeEvent;
use App\Events\AdminScoreboardEvent;
use App\Events\AdminTasksEvent;
use App\Events\AdminTeamsEvent;
use App\Events\AppHomeEvent;
use App\Events\AppScoreboardEvent;
use App\Events\AppStatisticEvent;
use App\Events\AppStatisticIDEvent;
use App\Events\ProjectorEvent;
use App\Models\CheckTasks;
use App\Models\SolvedTasks;
use App\Models\Tasks;
use App\Models\Teams;

class EventsService
{

    public function __construct(private Utility $utility)
    {
    }
    public function adminEventsUsers(): void
    {
        $teams = Teams::all();
        $tasks = Tasks::all();
        
        $checkTasks = CheckTasks::all();
        $solvedTasks = SolvedTasks::all();

        $universalResult = $this->utility->processTasksUniversal($tasks);
        $infoTasks = $this->utility->formatToLegacyUniversal($universalResult);

        $dataHome = compact('teams', 'tasks', 'infoTasks', 'checkTasks');
        $dataScoreboard = compact('teams', 'solvedTasks');

        AdminHomeEvent::dispatch($dataHome);
        AdminScoreboardEvent::dispatch($dataScoreboard);
    }

    public function adminEvents(): void
    {
        $teams = Teams::all()->makeVisible('token');
        $tasks = Tasks::all()->makeVisible('flag');
        
        $checkTasks = CheckTasks::all();
        $solvedTasks = SolvedTasks::all();

        $universalResult = $this->utility->processTasksUniversal($tasks);
        $infoTasks = $this->utility->formatToLegacyUniversal($universalResult);

        $dataHome = compact('teams', 'tasks', 'infoTasks', 'checkTasks');
        $dataScoreboard = compact('teams', 'solvedTasks');

        $this->utility->cacheClear();

        AdminHomeEvent::dispatch($dataHome);

        AdminTasksEvent::dispatch($tasks);
        AdminTeamsEvent::dispatch($teams);

        AdminScoreboardEvent::dispatch($dataScoreboard);
    }

    public function appEvents(): void
    {
        $checkTasks = CheckTasks::all();
        $teams = Teams::all();
        $tasks = Tasks::all();
        $solvedTasks = SolvedTasks::all();

        $dataStatistic = compact('teams', 'tasks', 'solvedTasks', 'checkTasks');
        $dataHome = compact('tasks', 'solvedTasks');
        $dataScoreboard = compact('teams', 'solvedTasks');

        $this->utility->cacheClear();

        AppHomeEvent::dispatch($dataHome);

        AppStatisticIDEvent::dispatch($dataStatistic);
        AppStatisticEvent::dispatch($teams);

        AppScoreboardEvent::dispatch($dataScoreboard);
        ProjectorEvent::dispatch($dataScoreboard);
    }
}

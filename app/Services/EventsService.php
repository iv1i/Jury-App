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
use App\Models\CompletedTaskTeams;
use App\Models\SolvedTasks;
use App\Models\Tasks;
use App\Models\Teams;

class EventsService
{

    public function __construct(private Utility $utility)
    {
    }

    public function appEventsUsers(): void
    {
        $CHKT = CheckTasks::all();
        $Team = Teams::all();
        $Tasks = Tasks::all();
        $DesidedT = CompletedTaskTeams::all();
        $SolvedTasks = SolvedTasks::all();
        $Teams = $Team;
        $data = compact('Team', 'Tasks', 'SolvedTasks', 'CHKT');
        $data2 = compact('Tasks', 'SolvedTasks');
        $data3 = compact('Teams', 'DesidedT');

        AppHomeEvent::dispatch($data2);
        AppStatisticIDEvent::dispatch($data);
        AppScoreboardEvent::dispatch($data3);
        AppStatisticEvent::dispatch($Team);
        ProjectorEvent::dispatch($data3);

    }
    public function adminEventsUsers(): void
    {
        $Teams = Teams::all()->makeVisible('token');
        $Tasks = Tasks::all()->makeVisible('flag');
        $universalResult = $this->utility->processTasksUniversal($Tasks);
        $InfoTasks = $this->utility->formatToLegacyUniversal($universalResult);
        $CheckTasks = CheckTasks::all();
        $DesidedT = CompletedTaskTeams::all();
        $data = [$Tasks, $Teams, $InfoTasks, $CheckTasks];
        $data3 = compact('Teams', 'DesidedT');
        AdminHomeEvent::dispatch($data);
        AdminScoreboardEvent::dispatch($data3);
    }

    public function adminEvents(): void
    {
        $Teams = Teams::all()->makeVisible('token');
        $Tasks = Tasks::all()->makeVisible('flag');
        $universalResult = $this->utility->processTasksUniversal($Tasks);
        $InfoTasks = $this->utility->formatToLegacyUniversal($universalResult);
        $CheckTasks = CheckTasks::all();
        $DesidedT = CompletedTaskTeams::all();
        $dataHome = [$Tasks, $Teams, $InfoTasks, $CheckTasks];
        $dataScoreboard = [$Teams, $DesidedT];

        $this->utility->cacheClear();

        AdminTasksEvent::dispatch($Tasks);
        AdminTeamsEvent::dispatch($Teams);
        AdminHomeEvent::dispatch($dataHome);
        AdminScoreboardEvent::dispatch($dataScoreboard);
    }

    public function appEvents(): void
    {
        $CHKT = CheckTasks::all();
        $Team = Teams::all();
        $Tasks = Tasks::all();
        $DesidedT = CompletedTaskTeams::all();
        $SolvedTasks = SolvedTasks::all();
        $Teams = $Team;
        $data = compact('Team', 'Tasks', 'SolvedTasks', 'CHKT');
        $data2 = compact('Tasks', 'SolvedTasks');
        $data3 = compact('Teams', 'DesidedT');
        $this->utility->cacheClear();

        AppStatisticIDEvent::dispatch($data);
        AppHomeEvent::dispatch($data2);
        AppScoreboardEvent::dispatch($data3);
        AppStatisticEvent::dispatch($Team);
        ProjectorEvent::dispatch($data3);
    }
}

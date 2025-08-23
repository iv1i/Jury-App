<?php

namespace App\Services;

use App\Events\AdminHomeEvent;
use App\Events\AdminScoreboardEvent;
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

    public function appEvents()
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
    public function adminEvents()
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
}

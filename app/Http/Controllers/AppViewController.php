<?php

namespace App\Http\Controllers;

use App\Models\CompletedTaskTeams;
use App\Models\SolvedTasks;
use App\Models\Tasks;
use App\Models\Teams;
use App\Repositories\CompletedTasksTeamsRepository;
use App\Repositories\TaskRepository;
use App\Repositories\TeamsRepository;
use App\Services\SettingsService;
use App\Services\Utility;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AppViewController extends Controller
{
    public function __construct(
        private Utility $utility,
        private TaskRepository $taskRepository,
        private TeamsRepository $teamsRepository,
    ) {}

    public function home(): View
    {
        $teamId = auth()->id();

        $tasks = $this->taskRepository->getAllTasks();
        $solvedTasks = Teams::find(auth()->id())->solvedTasks;

        $universalResult = $this->utility->processTasksUniversal($tasks);

        $categories = $universalResult['categories'] ?? [];
        ksort($categories);

        $complexities = $universalResult['difficulty'] ?? [];
        ksort($complexities);



        return view('App.AppHome', compact(
            'tasks',
            'categories',
            'complexities',
            'solvedTasks',
            'teamId',
        ));
    }

    public function statisticById(int $id): View|RedirectResponse
    {
        $team = Teams::with([
            'solvedTasks.tasks',
            'checkTasks'
        ])->find($id);

        if (!$team) {
            return redirect()->back()->with('error', 'Команда не найдена');
        }

        $teamSolvedTasks = $team->solvedTasks
            ->pluck('tasks')
            ->filter()
            ->values();

        $teams = $this->teamsRepository->getAllTeams()
            ->map(function($team) {
                $team->teamlogo = $team->teamlogo
                    ? asset('storage/teamlogo/' . $team->teamlogo)
                    : asset('storage/teamlogo/StandartLogo.png');

                return $team;
            });

        $tasks = Tasks::all();

        $checkTasks = $team->checkTasks;

        $teamLogoUrl = $team->teamlogo
            ? asset('storage/teamlogo/' . $team->teamlogo)
            : asset('storage/teamlogo/StandartLogo.png');

        return view('App.AppStatisticID', compact(
            'id',
            'teams',
            'tasks',
            'checkTasks',
            'teamSolvedTasks',
            'team',
            'teamLogoUrl',
        ));
    }

    public function statistic(): View
    {
        $teams = $this->teamsRepository->getAllTeams();
        $teamId = auth()->id();

        return view('App.AppStatistic', compact(
            'teams',
            'teamId'
            ));
    }

    public function scoreboard(): View
    {
        $teams = $this->teamsRepository->getAllTeams();
        $teamId = auth()->id();
        $solvedTasks = SolvedTasks::all();

        return view('App.AppScoreboard', compact(
            'teams',
            'teamId',
            'solvedTasks',
        ));
    }

}

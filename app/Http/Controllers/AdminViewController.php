<?php

namespace App\Http\Controllers;

use App\Models\CheckTasks;
use App\Repositories\CompletedTasksTeamsRepository;
use App\Repositories\TaskRepository;
use App\Repositories\TeamsRepository;
use App\Services\SettingsService;
use App\Services\Utility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminViewController extends Controller
{

    public function __construct(
        private SettingsService $settings,
        private Utility $utility,
        private TaskRepository $taskRepository,
        private TeamsRepository $teamsRepository,
        private CompletedTasksTeamsRepository $completedTasksTeamsRepository
    ) {}

    public function adminScoreboardView(): \Illuminate\Contracts\View\View
    {
        $teams = $this->teamsRepository->getAllTeams();
        $completedTasksTeams = $this->completedTasksTeamsRepository->getAllCompletedTasksAndTeams();

        return view('Admin.AdminScoreboard', compact('teams', 'completedTasksTeams'));
    }

    public function adminTeamsView(): \Illuminate\Contracts\View\View
    {
        $teams = $this->teamsRepository->getAllTeamsNoHidden();

        return view('Admin.AdminTeams', compact('teams'));
    }

    public function adminTasksView(): \Illuminate\Contracts\View\View
    {
        $tasks = $this->taskRepository->getAllTasksNoHidden();

        $universalResult = $this->utility->processTasksUniversal($tasks);
        $infoTasks = $this->utility->formatToLegacyUniversal($universalResult);

        $categories = $universalResult['categories'] ?? [];
        ksort($categories);

        $complexities = $universalResult['difficulty'] ?? [];
        ksort($complexities);

        $allComplexities = $this->settings->get('complexity');
        $allCategories = $this->settings->get('categories');

        return view('Admin.AdminTasks', compact(
            'tasks',
            'categories',
            'complexities',
            'infoTasks',
            'allComplexities',
            'allCategories',
        ));
    }

    public function adminSettingsView(): \Illuminate\Contracts\View\View
    {
        $rules = $this->settings->get('AppRulesTB') ?? '(•ิ_•ิ)?';
        $settingsSidebar = $this->settings->get('sidebar');
        $authType = $this->settings->get('auth');

        return view('Admin.AdminSettings', compact('rules', 'settingsSidebar', 'authType'));
    }

    public function adminHomeView(): \Illuminate\Contracts\View\View
    {
        $teams = $this->teamsRepository->getAllTeams();
        $tasks = $this->taskRepository->getAllTasks();
        $universalResult = $this->utility->processTasksUniversal($tasks);
        $infoTasks = $this->utility->formatToLegacyUniversal($universalResult);
        $checkTasks = CheckTasks::all();

        return view('Admin.AdminHome', compact(
            'teams',
            'tasks',
            'infoTasks',
            'checkTasks',
        ));
    }

}

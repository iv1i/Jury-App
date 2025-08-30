<?php

namespace App\Http\Controllers;

use App\Models\CheckTasks;
use App\Models\CompletedTaskTeams;
use App\Models\SolvedTasks;
use App\Repositories\CompletedTasksTeamsRepository;
use App\Repositories\TaskRepository;
use App\Repositories\TeamsRepository;
use App\Services\SettingsService;
use App\Services\Utility;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestViewController extends Controller
{
    public function __construct(
        private SettingsService $settings,
        private TeamsRepository $teamsRepository,
    ) {}

    public function projectorView(): View
    {
        $teams = $this->teamsRepository->getAllTeams();

        $checkTasks = CheckTasks::all();

        $solvedTasks = SolvedTasks::all();

        return view('Guest.projectorTB', compact('teams', 'checkTasks', 'solvedTasks'));
    }

    public function rulesView(): View
    {
        $rules = $this->settings->get('AppRulesTB') ?? '(•ิ_•ิ)?';

        if (Auth::check()) {
            return view('App.AppRules', compact('rules'));
        }

        return view('Guest.rules', compact('rules'));
    }

    public function authView(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect('/Home');
        }

        return view('App.AppAuth');
    }
    public function adminAuthView(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect('/Admin');
        }

        return view('Admin.AdminAuth');
    }

}

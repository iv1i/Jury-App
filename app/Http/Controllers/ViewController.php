<?php

namespace App\Http\Controllers;

use App\Models\CheckTasks;
use App\Models\CompletedTaskTeams;
use App\Models\Tasks;
use App\Models\Teams;
use App\Repositories\CompletedTasksTeamsRepository;
use App\Repositories\TaskRepository;
use App\Repositories\TeamsRepository;
use App\Services\SettingsService;
use App\Services\Utility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class ViewController extends Controller
{
    public function slashView(): \Illuminate\Contracts\View\View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect('/Home');
        }

        return redirect('/Auth');
    }

}

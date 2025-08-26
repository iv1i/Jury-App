<?php

namespace App\Http\Controllers;

use App\Events\AdminHomeEvent;
use App\Events\AdminScoreboardEvent;
use App\Events\AdminTasksEvent;
use App\Events\AdminTeamsEvent;
use App\Events\AppHomeEvent;
use App\Events\AppScoreboardEvent;
use App\Events\AppStatisticEvent;
use App\Events\AppStatisticIDEvent;
use App\Events\ProjectorEvent;
use App\Events\UpdateRulesEvent;
use App\Models\CheckTasks;
use App\Models\CompletedTaskTeams;
use App\Models\SolvedTasks;
use App\Models\Tasks;
use App\Models\Teams;
use App\Services\AdminService;
use App\Services\SettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use ZipArchive;

class AdminController extends Controller
{
    public function __construct(private AdminService  $adminService)
    {
    }

    // ----------------------------------------------------------------TEAMS
    public function addTeams(Request $request): JsonResponse
    {
        $resp = $this->adminService->addTeams($request);

        return response()->json($resp, $resp['status']);
    }
    public function deleteTeams(Request $request): JsonResponse
    {
        $resp = $this->adminService->deleteTeams($request);

        return response()->json($resp, $resp['status']);
    }
    public function changeTeams(Request $request): JsonResponse
    {
        $resp = $this->adminService->changeTeams($request);

        return response()->json($resp, $resp['status']);
    }

    // ----------------------------------------------------------------TASKS

    public function addTasks(Request $request): JsonResponse
    {
        $resp = $this->adminService->addTasks($request);

        return response()->json($resp, $resp['status']);
    }
    public function changeTasks(Request $request): JsonResponse
    {
        $resp = $this->adminService->changeTasks($request);

        return response()->json($resp, $resp['status']);
    }
    public function deleteTasks(Request $request): JsonResponse
    {
        $resp = $this->adminService->deleteTasks($request);

        return response()->json($resp, $resp['status']);
    }

    // ----------------------------------------------------------------SETTINGS
    public function settingsReset(Request $request): JsonResponse
    {
        $resp = $this->adminService->settingsReset($request);

        return response()->json($resp, $resp['status']);
    }
    public function settingsDeleteAll(Request $request): JsonResponse
    {
        $resp = $this->adminService->settingsDeleteAll($request);

        return response()->json($resp, $resp['status']);
    }
    public function settingsChangeCategory(Request $request): JsonResponse
    {
        $resp = $this->adminService->settingsChangeCategory($request);

        return response()->json($resp, $resp['status']);
    }
    public function settingsChangeRules(Request $request): JsonResponse
    {
        $resp = $this->adminService->settingsChangeRules($request);

        return response()->json($resp, $resp['status']);
    }
    public function settingsSidebars(Request $request): JsonResponse
    {
        $resp = $this->adminService->settingsSidebars($request);

        return response()->json($resp, $resp['status']);
    }

}


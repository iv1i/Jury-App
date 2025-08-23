<?php

namespace App\Http\Controllers;

use App\Events\{AdminHomeEvent, AdminScoreboardEvent, AppCheckTaskEvent, AppHomeEvent, AppScoreboardEvent, AppStatisticEvent, AppStatisticIDEvent, ProjectorEvent};
use App\Services\AppService;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\{Tasks, SolvedTasks, Teams, CompletedTaskTeams, CheckTasks};
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Auth, Storage, Cache, DB, Validator};


class AppController extends Controller
{
    public function __construct(private AppService $appService)
    {
    }

    //----------------------------------------------------------------APP

    public function checkFlag(Request $request): JsonResponse
    {
        $resp = $this->appService->checkFlag($request);

        return response()->json($resp, $resp['status']);
    }

    public function downloadFile($md5file, $id, Request $request): JsonResponse|StreamedResponse
    {
        $file = $this->appService->downloadFile($md5file, $id, $request);

        if ($file['status'] === 200) {
            return Storage::disk('private')->download($file['filepath'], $file['filename']);
        }

        if ($file['status'] === 404) {
            abort(404);
        }

        abort(400);
    }

}

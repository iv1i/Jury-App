<?php

namespace App\Services;

use App\Events\AdminHomeEvent;
use App\Events\AdminScoreboardEvent;
use App\Events\AppCheckTaskEvent;
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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AppService
{

    public function __construct(private Utility $utility, private EventsService $eventsService)
    {
    }

    //----------------------------------------------------------------APP
    public function checkFlag(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'flag' => ['required', 'string', 'max:255'],
            'ID' => ['required', 'numeric', 'integer'],
        ]);

        if ($validator->fails()) {
            return [
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'status' => 422
                ];
        }

        $taskId = $request->input('ID');
        $task = Tasks::findOrFail($taskId);
        $authUserId = Auth::id();

        if ($this->isTaskAlreadySolved($authUserId, $taskId)) {
            return [
                'type' => 'warning',
                'success' => true,
                'message' => 'Вы уже решили эту задачу',
                'status' => 200
            ];
        }

        if ($request->input('flag') !== $task->flag) {
            return [
                'success' => false,
                'message' => 'Флаг неверный!',
                'status' => 200
            ];
        }

        $this->handleCorrectFlag($task, $authUserId, $request->input('complexity'));

        return [
            'success' => true,
            'message' => __('The flag is correct!'),
            'status' => 200
        ];
    }

    private function isTaskAlreadySolved(int $userId, int $taskId): bool
    {
        return SolvedTasks::where('teams_id', $userId)
            ->where('tasks_id', $taskId)
            ->exists();
    }

    private function handleCorrectFlag(Tasks $task, int $userId, ?string $complexity): void
    {
        $this->createSolvedTask($task, $userId);
        $this->updateTaskPrice($task);
        $this->updateUserStats($userId, $task->id, $complexity);
        $this->updateAllUsersScores();

        $this->eventsService->appEventsUsers();
        $this->eventsService->adminEventsUsers();

        $this->utility->cacheClear();
    }

    private function createSolvedTask(Tasks $task, int $userId): void
    {
        $solvedTask = new SolvedTasks();
        $solvedTask->id = $this->utility->makeId(SolvedTasks::all());
        $solvedTask->teams_id = $userId;
        $solvedTask->tasks_id = $task->id;
        $solvedTask->price = $task->id;
        $solvedTask->save();
    }

    private function updateTaskPrice(Tasks $task): void
    {
        $solvedCount = $task->solved + 1;
        $teamsCount = DB::table('teams')->count();
        $rate = $solvedCount / $teamsCount;

        if($rate >= 0.2 && $rate < 0.4){
            $task->price = $task->oldprice - $task->oldprice *0.2;
        }
        if($rate >= 0.4 && $rate < 0.6){
            $task->price = $task->oldprice - $task->oldprice *0.4;
        }
        if($rate >= 0.6 && $rate < 0.8){
            $task->price = $task->oldprice - $task->oldprice *0.6;
        }
        if($rate >= 0.8){
            $task->price = $task->oldprice - $task->oldprice *0.8;
        }

        $task->solved = $solvedCount;
        $task->save();
    }

    private function updateUserStats(int $userId, int $taskId, ?string $complexity): void
    {
        $checkTasks = Teams::findOrFail($userId)->checkTasks;

        if (!$checkTasks) {
            return;
        }

        $checkTasks->sumary += 1;

        $styleMap = [
            'easy' => '<div id="easy" style="background-color: #2ba972; border-radius: 5px; text-align: center; width: 10px; height: 20px; margin-right: 4px"><b></b></div>',
            'medium' => '<div id="medium" style="background-color: #0086d3; border-radius: 5px; text-align: center; width: 10px; height: 20px; margin-right: 4px"><b></b></div>',
            'hard' => '<div id="hard" style="background-color: #ba074f; border-radius: 5px; text-align: center; width: 10px; height: 20px; margin-right: 4px"><b></b></div>'
        ];

        if (array_key_exists($complexity, $styleMap)) {
            $checkTasks->{$complexity} += 1;

            $CompletedTaskTeams = new CompletedTaskTeams();
            $CompletedTaskTeams->id = $this->utility->makeId(CompletedTaskTeams::all());
            $CompletedTaskTeams->tasks_id = $taskId;
            $CompletedTaskTeams->teams_id = $userId;
            $CompletedTaskTeams->StyleTask = $styleMap[$complexity];
            $CompletedTaskTeams->save();
        }

        $checkTasks->save();
    }

    private function updateAllUsersScores(): void
    {
        $users = Teams::with(['solvedTasks.tasks'])->get();

        foreach ($users as $user) {
            $user->scores = $user->solvedTasks->sum(function ($solvedTask) {
                return $solvedTask->tasks->price ?? 0;
            });
            $user->save();
        }
    }

    public function downloadFile($md5file, $id, Request $request): array
    {
        $task = Tasks::find($id);

        $FILES = $task->FILES;
        $arrayfiles = explode(";", $FILES);

        foreach ($arrayfiles as $k => $f){
            if(md5($f) === $md5file){
                // Получаем относительный путь к файлу
                $filePath = 'TasksFiles/' . $f;

                // Проверяем, существует ли файл
                if (!Storage::disk('private')->exists($filePath)) {
                    abort(404); // Файл не найден
                }
                $extension = pathinfo($f, PATHINFO_EXTENSION);
                $name = 'file_' . $task->name  . '_'. $k+1 . '.' . $extension;
                // Загружаем файл
                return [
                    'success' => true,
                    'filename' => $name,
                    'filepath' => $filePath,
                    'status' => 200
                ];
            }
        }
        return [
            'success' => false,
            'status' => 404
        ];
    }

    //----------------------------------------------------------------OLD
    public function СheckFlagOLD(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'flag' => ['required', 'string', 'max:255'],
            'ID' => ['required', 'numeric', 'integer'],
        ]);

        if ($validator->fails()) {
            $firstErrorMessage = $validator->errors()->first();
            return response()->json(['message' => $firstErrorMessage], 422);
        }

        $userAgent = $request->userAgent();
        $taskid = $request->input('ID');
        $task = Tasks::findOrFail($taskid);
        $AuthUserId =Auth::user()['id'];

        $solved = SolvedTasks::where('teams_id', $AuthUserId)->where('tasks_id', $taskid)->exists();
        if ($solved) {
            return response()->json(['type' => 'warning', 'success' => 'true','message' => 'Вы уже решили эту задачу'], 200);
        }

        if($request->input('flag') === $task->flag){
            // Выполняем действия, если флаг верный
            $q = SolvedTasks::all();
            $SolvedId = $this->utility->makeId($q);

            $solvedtask = New SolvedTasks();
            $solvedtask->id = $SolvedId;
            $solvedtask->teams_id = $AuthUserId;
            $solvedtask->tasks_id = $taskid;
            $solvedtask->price = $taskid;
            $solvedtask->save();
            $solved = $task->solved + 1;
            $countteams = DB::table('teams')->count();
            $rate = $solved/$countteams;

            if($rate >= 0.2 && $rate < 0.4){
                $task->price = $task->oldprice - $task->oldprice *0.2;
            }
            if($rate >= 0.4 && $rate < 0.6){
                $task->price = $task->oldprice - $task->oldprice *0.4;
            }
            if($rate >= 0.6 && $rate < 0.8){
                $task->price = $task->oldprice - $task->oldprice *0.6;
            }
            if($rate >= 0.8){
                $task->price = $task->oldprice - $task->oldprice *0.8;
            }
            $task->solved = $solved;
            $task->save();
            $checktasks = Teams::findOrFail($AuthUserId)->checkTasks;

            $easyTask = '<div id="easy" style="background-color: #2ba972; border-radius: 5px; Text-align: center; width: 10px; height: 20px; margin-right: 4px"> <b></b> </div>';
            $mediumTask = '<div id="medium" style="background-color: #0086d3; border-radius: 5px; Text-align: center; width: 10px; height: 20px; margin-right: 4px"> <b></b> </div>';
            $hardTask = '<div id="hard" style="background-color: #ba074f; border-radius: 5px; Text-align: center; width: 10px; height: 20px; margin-right: 4px"> <b></b> </div>';

            $desidedTask = New CompletedTaskTeams();

            $q = CompletedTaskTeams::all();
            $DesideId = $this->utility->makeId($q);

            $desidedTask->id = $DesideId;
            $desidedTask->tasks_id = $taskid;
            $desidedTask->teams_id = $AuthUserId;
            //dd($checktasks);
            if ($checktasks) {
                $checktasks->sumary += 1;

                //complexity
                if ($request->input('complexity') === 'easy') {
                    $checktasks->easy += 1;
                    $desidedTask->StyleTask = $easyTask;
                }
                if ($request->input('complexity') === 'medium') {
                    $checktasks->medium += 1;
                    $desidedTask->StyleTask = $mediumTask;
                }
                if ($request->input('complexity') === 'hard') {
                    $checktasks->hard += 1;
                    $desidedTask->StyleTask = $hardTask;
                }
                $checktasks->save();
                $desidedTask->save();

                $user = Teams::with(['solvedTasks.tasks'])->find($AuthUserId);

                $scores = $user->solvedTasks->sum(function($solvedTask) {
                    return $solvedTask->tasks->price ?? 0;
                });

                $user->scores = $scores;
                $user->save();
                $userAll = Teams::with(['solvedTasks.tasks'])->get();

                foreach ($userAll as $user) {
                    $scores = $user->solvedTasks->sum(function($solvedTask) {
                        return $solvedTask->tasks->price ?? 0;
                    });

                    $user->scores = $scores;
                    $user->save();
                }

            }

            $this->eventsService->appEventsUsers();
            $this->eventsService->adminEventsUsers();

            $this->utility->cacheClear();

            return response()->json(['success' => true,'message' =>  __('The flag is correct!')], 200);

        }
        return response()->json(['success' => false,'message' => 'Флаг неверный!'], 200);
    }
}

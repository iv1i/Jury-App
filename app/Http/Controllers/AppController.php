<?php

namespace App\Http\Controllers;

use App\Events\{AdminHomeEvent, AdminScoreboardEvent, AppCheckTaskEvent, AppHomeEvent, AppScoreboardEvent, AppStatisticEvent, AppStatisticIDEvent, ProjectorEvent};
use App\Models\{Tasks, SolvedTasks, Teams, CompletedTaskTeams, CheckTasks};
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Auth, Storage, Cache, DB, Validator};


class AppController extends Controller
{
    //----------------------------------------------------------------APP
    public function checkFlag(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'flag' => ['required', 'string', 'max:255'],
            'ID' => ['required', 'numeric', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['message' => $validator->errors()->first()],
                422
            );
        }

        $taskId = $request->input('ID');
        $task = Tasks::findOrFail($taskId);
        $authUserId = Auth::id();

        if ($this->isTaskAlreadySolved($authUserId, $taskId)) {
            return response()->json([
                'type' => 'warning',
                'success' => true,
                'message' => 'Вы уже решили эту задачу'
            ], 200);
        }

        if ($request->input('flag') !== $task->flag) {
            return response()->json([
                'success' => false,
                'message' => 'Флаг неверный!'
            ], 200);
        }

        $this->handleCorrectFlag($task, $authUserId, $request->input('complexity'));

        return response()->json([
            'success' => true,
            'message' => __('The flag is correct!')
        ], 200);
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

        $this->appEvents();
        $this->adminEvents();
        $this->cacheClear();
    }

    private function createSolvedTask(Tasks $task, int $userId): void
    {
        $solvedTask = new SolvedTasks();
        $solvedTask->id = $this->makeId(SolvedTasks::all());
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
            $CompletedTaskTeams->id = $this->makeId(CompletedTaskTeams::all());
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
    public function СheckFlag(Request $request)
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
            $SolvedId = $this->makeId($q);

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
            $DesideId = $this->makeId($q);

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

            $this->appEvents();
            $this->adminEvents();

            $this->cacheClear();

            return response()->json(['success' => true,'message' =>  __('The flag is correct!')], 200);

        }
        return response()->json(['success' => false,'message' => 'Флаг неверный!'], 200);
    }
    public function downloadFile($md5file, $id, Request $request)
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
                return Storage::disk('private')->download($filePath, $name);
            }
        }
        abort(404); // файл не найден
    }

    //----------------------------------------------------------------EVENTS
    public function notifEventsSucces($agent)
    {
        $id = Auth::id();
        $message = __('Success');
        $text = __('The flag is correct!');
        $color = '#40f443';
        $userAgent = $agent;
        $notification = compact('message', 'text', 'color', 'id', 'userAgent');
        AppCheckTaskEvent::dispatch($notification);
    }
    public function notifEventsOups($agent)
    {
        $id = Auth::id();
        $message = __('Oups!');
        $text = __('You have already solved this problem!');
        $color = '#ffc200';
        $userAgent = $agent;
        $notification = compact('message', 'text', 'color', 'id', 'userAgent');
        AppCheckTaskEvent::dispatch($notification);
    }
    public function notifEventsError($agent)
    {
        $id = Auth::id();
        $message = __('Error');
        $text = __('The wrong flag!');
        $color = '#f4406a';
        $userAgent = $agent;
        $notification = compact('message', 'text', 'color', 'id', 'userAgent');
        AppCheckTaskEvent::dispatch($notification);
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
        $universalResult = $this->processTasksUniversal($Tasks);
        $InfoTasks = $this->formatToLegacyUniversal($universalResult);
        $CheckTasks = CheckTasks::all();
        $DesidedT = CompletedTaskTeams::all();
        $data = [$Tasks, $Teams, $InfoTasks, $CheckTasks];
        $data3 = compact('Teams', 'DesidedT');
        AdminHomeEvent::dispatch($data);
        AdminScoreboardEvent::dispatch($data3);
    }
    //----------------------------------------------------------------OTHER
    private function cacheClear()
    {
        Cache::tags('ModelList')->flush();
    }
    private function makeId($q)
    {
        $table = [];
        foreach ($q as $item) {
            $table[] = $item->id;
        }
        $id = 1;

        foreach ($table as $value) {
            if ($value != $id) {
                break;
            }
            $id++;
        }
        return $id;
    }
    private function formatToLegacyUniversal($universalResult) {
        // Сначала создаем массив только с sumary
        $legacy = [
            'sumary' => $universalResult['sumary'] ?? 0
        ];

        // Стандартные категории из legacy-формата (для обратной совместимости)
        $legacyCategories = [
            'admin', 'recon', 'crypto', 'stegano', 'ppc', 'pwn',
            'web', 'forensic', 'joy', 'misc', 'osint', 'reverse',
            'easy', 'medium', 'hard' // Добавляем сложности в категории для сортировки
        ];

        // Собираем все возможные категории
        $allCategories = array_unique(array_merge(
            $legacyCategories,
            array_keys($universalResult['categories'] ?? [])
        ));

        // Сортируем категории в алфавитном порядке
        sort($allCategories);

        // Добавляем категории в отсортированном порядке
        foreach ($allCategories as $category) {
            // Для сложностей берем из difficulty
            if (in_array($category, ['easy', 'medium', 'hard'])) {
                $legacy[$category] = $universalResult['difficulty'][$category] ?? 0;
            }
            // Для остальных категорий берем из categories
            else {
                $legacy[$category] = $universalResult['categories'][$category] ?? 0;
            }
        }

        return $legacy;
    }
    private function processTasksUniversal($tasks) {
        $result = [
            'sumary' => 0,
            'difficulty' => [],
            'categories' => []
        ];

        foreach ($tasks as $task) {
            $result['sumary']++;

            // Обработка сложности
            $difficulty = strtolower($task['complexity'] ?? 'unknown');
            if (!isset($result['difficulty'][$difficulty])) {
                $result['difficulty'][$difficulty] = 0;
            }
            $result['difficulty'][$difficulty]++;

            // Обработка категорий (поддержка задач с несколькими категориями)
            $categories = $task['category'] ?? 'unknown';

            // Если категория передана как строка (одна категория)
            if (is_string($categories)) {
                $categories = array_map('trim', explode(',', $categories));
            }

            // Если категория передана как массив
            if (is_array($categories)) {
                foreach ($categories as $category) {
                    $category = strtolower(trim($category));
                    if (!isset($result['categories'][$category])) {
                        $result['categories'][$category] = 0;
                    }
                    $result['categories'][$category]++;
                }
            }
        }

        // Сортируем категории и сложности для удобства
        ksort($result['difficulty']);
        ksort($result['categories']);

        return $result;
    }
}

<?php

namespace App\Http\Controllers;

use App\Events\AdminHomeEvent;
use App\Events\AdminScoreboardEvent;
use App\Events\AppCheckTaskEvent;
use App\Events\AppHomeEvent;
use App\Events\AppScoreboardEvent;
use App\Events\AppStatisticEvent;
use App\Events\AppStatisticIDEvent;
use App\Events\ProjectorEvent;
use App\Models\CheckTasks;
use App\Models\desided_tasks_teams;
use App\Models\Settings;
use App\Models\SolvedTasks;
use App\Models\Tasks;
use App\Models\User;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AppController extends Controller
{
    //----------------------------------------------------------------APP
    public function CheckFlag(Request $request)
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
        //dd($task->flag);
        //$checkSolvedTasks = User::find($AuthUserId)->solvedTasks;

        $solved = SolvedTasks::where('user_id', $AuthUserId)->where('tasks_id', $taskid)->exists();
        if ($solved) {
            //$this->NotifEventsOups($userAgent);
            return response()->json(['type' => 'warning', 'success' => 'true','message' => 'Вы уже решили эту задачу'], 200);
            //return redirect()->route('TasksID', ['id' => $taskid])->with('error', 'Вы уже решили эту задачу');
        }

        //dd($checkSolvedTasks);
//        foreach ($checkSolvedTasks as $Task){
//            if($Task->tasks_id == $taskid){
//                //dd([$Task->tasks_id, $taskid]);
//                $this->NotifEventsOups();
//                return redirect()->route('TasksID', ['id' => $taskid])->with('error', 'Вы уже решили эту задачу');
//            }
//        }

        if($request->input('flag') === $task->flag){
            // Выполняем действия, если флаг верный
            $q = SolvedTasks::all();
            $SolvedId = $this->makeId($q);

            $solvedtask = New SolvedTasks();
            $solvedtask->id = $SolvedId;
            $solvedtask->user_id = $AuthUserId;
            $solvedtask->tasks_id = $taskid;
            $solvedtask->price = $taskid;
            $solvedtask->save();
            $solved = $task->solved + 1;
            $countteams = DB::table('users')->count();
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

            $checktasks = User::findOrFail($AuthUserId)->checkTasks;

            $easyTask = '<div id="easy" style="background-color: #2ba972; border-radius: 5px; Text-align: center; width: 10px; height: 20px; margin-right: 4px"> <b></b> </div>';
            $mediumTask = '<div id="medium" style="background-color: #0086d3; border-radius: 5px; Text-align: center; width: 10px; height: 20px; margin-right: 4px"> <b></b> </div>';
            $hardTask = '<div id="hard" style="background-color: #ba074f; border-radius: 5px; Text-align: center; width: 10px; height: 20px; margin-right: 4px"> <b></b> </div>';

            $desidedTask = New desided_tasks_teams();

            $q = desided_tasks_teams::all();
            $DesideId = $this->makeId($q);

            $desidedTask->id = $DesideId;
            $desidedTask->tasks_id = $taskid;
            $desidedTask->user_id = $AuthUserId;
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

                $user = User::find($AuthUserId);
                $solvedTasks = $user->solvedTasks;
                //dd($solvedTasks);
                $scores = 0;
                foreach ($solvedTasks as $task) {
                    $task = Tasks::find($task->tasks_id);
                    $scores += $task->price;
                }
                //dd($scores);
                $user->scores = $scores;
                $user->save();
                $userAll = User::all();
                foreach ($userAll as $user) {
                    $allusersolvedtasks = $user->solvedTasks;
                    $scores = 0;
                    foreach ($allusersolvedtasks as $task) {
                        $task = Tasks::find($task->tasks_id);
                        $scores += $task->price;
                    }
                    $user->scores = $scores;
                    $user->save();
                }

            }

            $this->AppEvents();
            $this->AdminEvents();

            return response()->json(['success' => true,'message' =>  __('The flag is correct!')], 200);
            //$this->NotifEventsSucces($userAgent);

        }

        //$this->NotifEventsError($userAgent);

        return response()->json(['success' => false,'message' => 'Флаг неверный!'], 200);
        //return redirect()->route('TasksID', ['id' => $taskid])->with('error', 'Флаг неверный!');
    }
    public function DwnlFile($md5file, $id, Request $request)
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
    public function NotifEventsSucces($agent)
    {
        $id = Auth::id();
        $message = __('Success');
        $text = __('The flag is correct!');
        $color = '#40f443';
        $userAgent = $agent;
        $notification = compact('message', 'text', 'color', 'id', 'userAgent');
        AppCheckTaskEvent::dispatch($notification);
    }
    public function NotifEventsOups($agent)
    {
        $id = Auth::id();
        $message = __('Oups!');
        $text = __('You have already solved this problem!');
        $color = '#ffc200';
        $userAgent = $agent;
        $notification = compact('message', 'text', 'color', 'id', 'userAgent');
        AppCheckTaskEvent::dispatch($notification);
    }
    public function NotifEventsError($agent)
    {
        $id = Auth::id();
        $message = __('Error');
        $text = __('The wrong flag!');
        $color = '#f4406a';
        $userAgent = $agent;
        $notification = compact('message', 'text', 'color', 'id', 'userAgent');
        AppCheckTaskEvent::dispatch($notification);
    }
    public function AppEvents()
    {
        $CHKT = CheckTasks::all();
        $Team = User::all();
        $Tasks = Tasks::all();
        $DesidedT = desided_tasks_teams::all();
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
    public function AdminEvents()
    {
        $Teams = User::all();
        $Tasks = Tasks::all();
        $universalResult = $this->processTasksUniversal($Tasks);
        $InfoTasks = $this->formatToLegacyUniversal($universalResult);
        $CheckTasks = CheckTasks::all();
        $DesidedT = desided_tasks_teams::all();
        $data = [$Tasks, $Teams, $InfoTasks, $CheckTasks];
        $data3 = compact('Teams', 'DesidedT');
        AdminHomeEvent::dispatch($data);
        AdminScoreboardEvent::dispatch($data3);
    }
    //----------------------------------------------------------------OTHER
    public function makeId($q)
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
    function formatToLegacyUniversal($universalResult) {
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
    function processTasksUniversal($tasks) {
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

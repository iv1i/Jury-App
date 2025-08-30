<?php

namespace App\Services;

use App\Events\UpdateRulesEvent;
use App\Http\Requests\AdminAddTasksRequest;
use App\Http\Requests\AdminAddTeamsRequest;
use App\Http\Requests\AdminChangeTasksRequest;
use App\Http\Requests\AdminChangeTeamsRequest;
use App\Models\CheckTasks;
use App\Models\CompletedTaskTeams;
use App\Models\SolvedTasks;
use App\Models\Tasks;
use App\Models\Teams;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class AdminService
{
    public function __construct(private SettingsService $settings, private Utility $utility, private EventsService $events)
    {
    }

    // ----------------------------------------------------------------TEAMS
    public function addTeams(AdminAddTeamsRequest $request): array
    {
        // Обработка значения IsGuest
        $isGuest = $request->input('IsGuest') === null ? 'No' : 'Yes';

        // Очистка входных данных
        $sanitizedName = htmlspecialchars($request->input('name'));
        $sanitizedPlayers = htmlspecialchars($request->input('players'));
        $sanitizedWhereFrom = htmlspecialchars($request->input('WhereFrom'));

        // Обработка файла логотипа
        $filename = 'StandartLogo.png';
        $checkFile = false;

        if ($request->file('file')) {
            $checkFile = true;
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            if($request->input('standartlogo')){
                $filename = 'StandartLogo.png';
            }
        }

        // Создание ID для новой команды
        $q = Teams::all();
        $userId = $this->utility->makeId($q);

        // Создание новой команды
        $team = new Teams();
        $team->id = $userId;
        $team->name = $sanitizedName;
        $team->password = Hash::make($request->input('password'));
        $team->token = md5($sanitizedName. Str::random(32) . $userId);
        $team->teamlogo = $filename;
        $team->players = $sanitizedPlayers;
        $team->wherefrom = $sanitizedWhereFrom;
        $team->guest = $isGuest;
        $team->scores = 0;
        $team->save();

        // Создание новой записи CheckTasks для команды
        $q = CheckTasks::all();
        $chkId = $this->utility->makeId($q);

        $chktasks = new CheckTasks();
        $chktasks->id = $chkId;
        $chktasks->teams_id = $userId;
        $chktasks->sumary = 0;
        $chktasks->easy = 0;
        $chktasks->medium = 0;
        $chktasks->hard = 0;
        $chktasks->save();

        // Сохранение файла логотипа
        if ($checkFile && !$request->input('standartlogo')) {
            $file->storeAs('teamlogo', $filename, 'public');
        }

        $tasks = Tasks::all();
        foreach ($tasks as $task) {
            $this->updateTaskPrice($task);
        }
        $this->updateTeamsScores();

        // Обновление событий
        $this->events->adminEvents();
        $this->events->appEvents();


        // Возврат ответа
        return [
            'success' => true,
            'message' => 'Команда успешно добавлена!',
            'status' => 200,
            ];
    }
    public function changeTeams(AdminChangeTeamsRequest $request): array
    {
        //dd($request->all());
        $TeamID = $request->input('id');
        $team = Teams::find($TeamID);

        if ($team){
            if ($request->input('IsGuest') === null){
                $IsGuest = 'No';
            }
            else{
                $IsGuest = 'Yes';
            }

            $sanitizedName = htmlspecialchars($request->input('name'));
            $sanitizedPlayers = htmlspecialchars($request->input('players'));
            $sanitizedWhereFrom = htmlspecialchars($request->input('WhereFrom'));

            if($request->input('standartlogo')){
                if($team->teamlogo !== 'StandartLogo.png'){
                    $deleteImage = 'public/teamlogo/' . $team->teamlogo;
                    Storage::delete($deleteImage);
                }
                $team->teamlogo = 'StandartLogo.png';
            }

            if ($request->file('file')) {
                $file = $request->file('file');
                $filename = time() . '_' . $file->getClientOriginalName();
                if(!$request->input('standartlogo')){
                    $file->storeAs('teamlogo', $filename, 'public');
                }
                else{
                    $filename = 'StandartLogo.png';
                }
                if($team->teamlogo !== 'StandartLogo.png'){
                    $deleteImage = 'public/teamlogo/' . $team->teamlogo;
                    Storage::delete($deleteImage);
                }
                $team->teamlogo = $filename;
            }

            $team->name = $sanitizedName;
            if($request->input('password') !== null){
                $team->password = Hash::make($request->input('password'));
                $team->token = md5($sanitizedName. Str::random(32) . $TeamID);
            }

            $team->players = $sanitizedPlayers;
            $team->wherefrom = $sanitizedWhereFrom;
            $team->guest = $IsGuest;
            $team->save();
        }
        else {
            return [
                'success' => false,
                'message' => 'Команда не найдена!',
                'status' => 200
            ];

        }

        $this->events->adminEvents();
        $this->events->appEvents();

        return [
            'success' => true,
            'message' => 'Команда успешно Обновлена!',
            'status' => 200
        ];
    }
    public function deleteTeams(Request $request): array
    {
        try {
            if (is_numeric($request->input('ID'))) {
                $user = Teams::findOrFail($request->input('ID'));
                $this->deleteTeam($user);
                $this->updateTeamsScores();
            } else {
                $str = $request->input('ID');
                $arr = explode('-', $str);

                if (count($arr) < 2) {
                    // Обработка ошибки, например, вернуть ответ с ошибкой.
                }

                $start = (int) $arr[0];
                $end = (int) $arr[1];
                if($start > $end) {
                    $E = $start;
                    $start = $end;
                    $end = $E;
                }
                $result = range($start, $end);

                foreach ($result as $id) {
                    $user = Teams::find($id);
                    if ($user) {
                        $this->deleteTeam($user);
                        $this->updateTeamsScores();
                    }
                }
            }

            $this->events->adminEvents();
            $this->events->appEvents();



            return [
                'success' => true,
                'message' => 'Команда успешно удалена/удалены!',
                'status' => 200
            ];
        } catch (\Exception $e) {
            // Обработка ошибки
            return [
                'success' => false,
                'message' => 'Ошибка при удалении!',
                'status' => 500
            ];
        }
    }
    private function deleteTeam(Teams $team): void
    {
        if ($team->teamlogo !== 'StandartLogo.png') {
            $deleteImage = 'public/teamlogo/' . $team->teamlogo;
            Storage::delete($deleteImage);
        }

        $tasks = $team->solvedTasks()->with('tasks')->get();
        if($tasks){
            foreach ($tasks as $task) {
                $task->tasks->solved--;
                $task->tasks->save();

                $this->updateTaskPriceDelete($task->tasks);
            }
        }

        $tasks = Tasks::all();
        foreach ($tasks as $task) {
            $this->updateTaskPriceDelete($task);
        }

        $team->checkTasks()->delete();

        $team->delete();
    }

    // ----------------------------------------------------------------TASKS
    public function addTasks(AdminAddTasksRequest $request): array
    {
        $sanitizedName = htmlspecialchars($request->input('name'));
        $sanitizedCategory = htmlspecialchars($request->input('category'));
        $sanitizedComplexity = htmlspecialchars($request->input('complexity'));
        $sanitizedDescription = $request->input('description');

        $q = Tasks::all();
        $id = $this->utility->makeId($q);

        // Обработка файлов|файла таска
        $filenames = null;
        if ($request->file('file')) {
            $file = $request->file('file');
            if ($file) {
                $Hashfile = md5(time() . '_' . $sanitizedName . '_' . $id . 'file');
                foreach ($file as $item) {
                    $filename = md5($Hashfile . '_' . $item->getClientOriginalName()) .'.'. $item->getClientOriginalExtension();
                    $filenames .= $filename . ';';
                    $item->storeAs('TasksFiles', $filename, 'private');
                }
            }
        }

        // Обработка веб-приложения
        $webDirectory = null;
        if ($request->has('web_port') && $request->input('web_port')) {
            $webDirectory = 'web_tasks/task_'.$id;
            Storage::disk('private')->makeDirectory($webDirectory);

            // Обработка исходного кода
            if ($request->file('sourcecode')) {
                $sourcecode = $request->file('sourcecode');
                $sourcecode->storeAs($webDirectory, 'source.zip', 'private');

                // Распаковка архива
                $zipPath = storage_path('app/private/'.$webDirectory.'/source.zip');
                $extractPath = storage_path('app/private/'.$webDirectory);

                $zip = new ZipArchive;
                $res = $zip->open($zipPath);
                if ($res === TRUE) {
                    try {
                        // Создаем директорию, если не существует
                        if (!file_exists($extractPath)) {
                            mkdir($extractPath, 0755, true);
                        }

                        // Извлекаем файлы
                        if ($zip->extractTo($extractPath)) {
                            $zip->close();
                            // Удаление zip-архива после распаковки (опционально)
                            unlink($zipPath);
                        } else {
                            throw new \Exception("Failed to extract ZIP archive");
                        }
                    } catch (\Exception $e) {
                        $zip->close();
                        Log::error("Error extracting ZIP archive: " . $e->getMessage() . " | Path: " . $zipPath);
                        // Можно добавить возврат ошибки пользователю
                        return [
                            'success' => false,
                            'message' => 'Ошибка распаковки архива',
                            'status' => 500
                        ];

                    }
                } else {
                    $errorMsg = "Failed to open ZIP archive (code $res): " . $zipPath;
                    Log::error($errorMsg);

                    return [
                        'success' => false,
                        'message' => 'Ошибка открытия архива',
                        'status' => 500
                    ];

                }
                $this->smartReplaceDockerPorts($webDirectory, $request->input('web_port'), $request->input('db_port'));
            }
        }
        elseif ($request->file('sourcecode')) {
            $webDirectory = 'web_tasks/task_'.$id;
            Storage::disk('private')->makeDirectory($webDirectory);
            $sourcecode = $request->file('sourcecode');
            $sourcecode->storeAs($webDirectory, 'source.zip', 'private');

            // Распаковка архива
            $zipPath = storage_path('app/private/'.$webDirectory.'/source.zip');
            $extractPath = storage_path('app/private/'.$webDirectory);

            $zip = new ZipArchive;
            $res = $zip->open($zipPath);
            if ($res === TRUE) {
                try {
                    // Создаем директорию, если не существует
                    if (!file_exists($extractPath)) {
                        mkdir($extractPath, 0755, true);
                    }

                    // Извлекаем файлы
                    if ($zip->extractTo($extractPath)) {
                        $zip->close();
                        // Удаление zip-архива после распаковки (опционально)
                        unlink($zipPath);
                    } else {
                        throw new \Exception("Failed to extract ZIP archive");
                    }
                } catch (\Exception $e) {
                    $zip->close();
                    Log::error("Error extracting ZIP archive: " . $e->getMessage() . " | Path: " . $zipPath);
                    // Можно добавить возврат ошибки пользователю
                    return [
                        'success' => false,
                        'message' => 'Ошибка распаковки архива',
                        'status' => 500
                    ];
                }
            } else {
                $errorMsg = "Failed to open ZIP archive (code $res): " . $zipPath;
                Log::error($errorMsg);

                return [
                    'success' => false,
                    'message' => 'Ошибка открытия архива',
                    'status' => 500
                ];
            }
        }

        $task = new Tasks();
        $task->id = $id;
        $task->name = $sanitizedName;
        $task->category = $sanitizedCategory;
        $task->complexity = $sanitizedComplexity;
        $task->price = $request->input('points');
        $task->oldprice = $request->input('points');
        $task->flag = $request->input('flag');
        $task->FILES = $filenames;
        $task->description = $sanitizedDescription;
        $task->web_port = $request->input('web_port');
        $task->db_port = $request->input('db_port');
        $task->web_directory = $webDirectory;
        $task->solved = 0;
        $task->save();

        $this->events->adminEvents();
        $this->events->appEvents();

        return [
            'success' => true,
            'message' => 'Таск успешно добавлен!',
            'status' => 200
        ];
    }
    public function changeTasks(AdminChangeTasksRequest $request): array
    {
        try {
            $task = Tasks::findOrFail($request->input('id'));
            $actions = [];

            // Санитизация входных данных
            $sanitizedName = htmlspecialchars($request->input('name'));
            $sanitizedCategory = htmlspecialchars($request->input('category'));
            $sanitizedComplexity = htmlspecialchars($request->input('complexity'));
            $sanitizedDescription = $request->input('description');

            // Обработка исходного кода
            $sourceCodeUpdated = $this->handleSourceCode($request, $task, $actions);

            // Обновление сложности и стилей решенных задач
            $complexityChanged = $this->handleComplexityChange($task, $request->input('complexity'), $actions);

            // Обработка файлов задачи
            $this->handleFiles($request, $task, $sanitizedName, $actions);

            // Обновление основных полей задачи
            $this->updateTaskFields($task, $request, $sanitizedName, $sanitizedCategory, $sanitizedComplexity, $sanitizedDescription, $actions);

            // Пересчет баллов пользователей
            $this->recalculateUserScores();

            // Обновление событий
            $this->events->adminEvents();
            $this->events->appEvents();

            return [
                'success' => true,
                'message' => 'Задача успешно обновлена!',
                'actions' => empty($actions) ? ['Настройки задачи сохранены (без изменений)'] : $actions,
                'status' => 200,
            ];

        } catch (\Exception $e) {
            Log::error("Error updating task: " . $e->getMessage());

            return [
                'success' => false,
                'error' => 'Ошибка при обновлении задачи',
                'details' => $e->getMessage(),
                'status' => 500
            ];
        }
    }

    private function handleSourceCode($request, $task, &$actions): bool
    {
        if (!$request->file('sourcecode')) {
            return false;
        }

        $webDirectory = $task->web_directory;
        $sourcecode = $request->file('sourcecode');

        // Сохранение ZIP архива
        $sourcecode->storeAs($webDirectory, 'source.zip', 'private');

        $zipPath = storage_path('app/private/'.$webDirectory.'/source.zip');
        $extractPath = storage_path('app/private/'.$webDirectory);

        $zip = new ZipArchive;
        if ($zip->open($zipPath) !== TRUE) {
            Log::error("Failed to open ZIP archive: " . $zipPath);
            throw new \Exception('Ошибка открытия архива');
        }

        try {
            if (!file_exists($extractPath)) {
                mkdir($extractPath, 0755, true);
            }

            if (!$zip->extractTo($extractPath)) {
                throw new \Exception("Failed to extract ZIP archive");
            }

            $zip->close();
            unlink($zipPath);

            $actions[] = 'Исходный код обновлен';

            // Обновление портов Docker если указаны
            if ($request->has('web_port') && $request->input('web_port')) {
                $this->smartReplaceDockerPorts(
                    $webDirectory,
                    $request->input('web_port'),
                    $request->input('db_port')
                );
                $actions[] = 'Порты Docker обновлены';
            }

            return true;

        } catch (\Exception $e) {
            $zip->close();
            Log::error("Error extracting ZIP archive: " . $e->getMessage());
            throw new \Exception('Ошибка распаковки архива');
        }
    }

    private function handleComplexityChange($task, $newComplexity, &$actions): bool
    {
        if ($task->complexity === $newComplexity) {
            return false;
        }

        $actions[] = 'Сложность изменена с ' . $task->complexity . ' на ' . $newComplexity;

        // Обновление стилей в решенных задачах
        $newStyle = $this->getComplexityStyle($newComplexity);

        SolvedTasks::where('tasks_id', $task->id)
            ->update(['style_tasks' => $newStyle]);

        // Обновление статистики пользователей
        $this->updateUserComplexityStats($task, $newComplexity);

        $actions[] = 'Статистика пользователей обновлена';

        return true;
    }

    private function getComplexityStyle($complexity): string
    {
        $styles = [
            'easy' => '<div id="easy" style="background-color: #2ba972; border-radius: 5px; text-align: center; width: 10px; height: 20px; margin-right: 4px"></div>',
            'medium' => '<div id="medium" style="background-color: #0086d3; border-radius: 5px; text-align: center; width: 10px; height: 20px; margin-right: 4px"></div>',
            'hard' => '<div id="hard" style="background-color: #ba074f; border-radius: 5px; text-align: center; width: 10px; height: 20px; margin-right: 4px"></div>'
        ];

        return $styles[$complexity] ?? $styles['medium'];
    }

    private function updateUserComplexityStats($task, $newComplexity): void
    {
        $solvedTasks = $task->solvedTasks;
        if ($solvedTasks->isEmpty()) {
            return;
        }

        $userIds = $solvedTasks->pluck('teams_id')->unique();
        $users = Teams::with('checkTasks')->whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            if (!$user->checkTasks) {
                continue;
            }

            $checkTasks = $user->checkTasks;
            $oldComplexity = strtolower($task->complexity);
            $newComplexityLower = strtolower($newComplexity);

            // Уменьшаем старую сложность
            if (isset($checkTasks->{$oldComplexity})) {
                $checkTasks->{$oldComplexity} = max(0, $checkTasks->{$oldComplexity} - 1);
            }

            // Увеличиваем новую сложность
            if (isset($checkTasks->{$newComplexityLower})) {
                $checkTasks->{$newComplexityLower} += 1;
            }

            $checkTasks->save();
        }
    }

    private function handleFiles($request, $task, $taskName, &$actions): void
    {
        // Удаление файлов если запрошено
        if ($request->input('deleteFilesFromTask')) {
            $this->deleteTaskFiles($task);
            $task->FILES = null;
            $actions[] = 'Все файлы удалены';
            return;
        }

        // Загрузка новых файлов
        if ($request->file('file')) {
            $this->deleteTaskFiles($task);

            $files = $request->file('file');
            $filenames = '';
            $fileHash = md5(time() . '_' . $taskName . '_' . $task->id . 'file');

            foreach ($files as $file) {
                $filename = md5($fileHash . '_' . $file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
                $filenames .= $filename . ';';
                $file->storeAs('TasksFiles', $filename, 'private');
            }

            $task->FILES = $filenames;
            $actions[] = 'Новые файлы загружены';
        }
    }

    private function deleteTaskFiles($task): void
    {
        if (!$task->FILES) {
            return;
        }

        $files = array_filter(explode(";", $task->FILES));
        foreach ($files as $file) {
            Storage::delete('private/TasksFiles/' . $file);
        }
    }

    private function updateTaskFields($task, $request, $name, $category, $complexity, $description, &$actions): void
    {
        $fieldsToCheck = [
            'name' => [$task->name, $name, 'Название обновлено'],
            'category' => [$task->category, $category, 'Категория обновлена'],
            'complexity' => [$task->complexity, $complexity, 'Сложность обновлена'],
            'description' => [$task->description, $description, 'Описание обновлено'],
            'oldprice' => [$task->oldprice, $request->input('points'), 'Баллы обновлены'],
        ];

        foreach ($fieldsToCheck as $field => [$oldValue, $newValue, $message]) {
            if ($oldValue != $newValue) {
                $task->$field = $newValue;
                $actions[] = $message;
            }
        }

        // Специальные поля
        $specialFields = ['flag', 'web_port', 'db_port'];
        foreach ($specialFields as $field) {
            if ($request->input($field) && $task->$field != $request->input($field)) {
                $task->$field = $request->input($field);
                $actions[] = ucfirst($field) . ' обновлен';
            }
        }

        $task->price = $request->input('points');
        $task->decide = null;
        $task->save();
    }

    private function recalculateUserScores(): void
    {
        $countTeams = Teams::count();
        if ($countTeams === 0) {
            return;
        }

        $users = Teams::with(['solvedTasks.tasks'])->get();

        foreach ($users as $user) {
            $totalScore = 0;

            foreach ($user->solvedTasks as $solvedTask) {
                if (!$solvedTask->tasks) {
                    continue;
                }

                $task = $solvedTask->tasks;
                $solvedCount = $task->solved ?? 0;
                $completionRate = $solvedCount / $countTeams;

                // Динамическое изменение цены задачи в зависимости от популярности
                $task->price = $this->calculateDynamicPrice($task->oldprice, $completionRate);
                $task->save();

                $totalScore += $task->price;
            }

            $user->scores = $totalScore;
            $user->save();
        }
    }

    private function calculateDynamicPrice($basePrice, $completionRate): float
    {
        if ($completionRate >= 0.8) return $basePrice * 0.2;
        if ($completionRate >= 0.6) return $basePrice * 0.4;
        if ($completionRate >= 0.4) return $basePrice * 0.6;
        if ($completionRate >= 0.2) return $basePrice * 0.8;

        return $basePrice;
    }

    public function deleteTasks(Request $request): array
    {
        try {
            if(is_numeric($request->input('ID'))){
                $task = Tasks::findOrFail($request->input('ID'));
                $this->DeleteTask($task);

                $FILES = $task->FILES;
                $arrayfiles = explode(";", $FILES);
                foreach ($arrayfiles as $file){
                    if($file){
                        $deleteFile = 'private/TasksFiles/' . $file;
                        Storage::delete($deleteFile);
                    }
                }
            }
            else {
                $str = $request->input('ID');
                $arr = explode('-', $str);
                $start = (int) $arr[0];
                $end = (int) $arr[1];
                if($start > $end) {
                    $E = $start;
                    $start = $end;
                    $end = $E;
                }
                $result = range($start, $end);

                foreach ($result as $id) {
                    $task = Tasks::find($id);
                    if($task){
                        $this->DeleteTask($task);
                    }
                    $FILES = $task->FILES;
                    $arrayfiles = explode(";", $FILES);
                    foreach ($arrayfiles as $file){
                        $deleteFile = 'private/TasksFiles/' . $file;
                        Storage::delete($deleteFile);
                    }
                }
            }
            $this->updateTeamsScores();
            $this->events->adminEvents();
            $this->events->appEvents();



            return [
                'success' => true,
                'message' => 'Таск/Таски удалены!',
                'status' => 200,
            ];

        } catch (\Exception $e) {
            // Обработка ошибки
            dd($e);
            return [
                'success' => false,
                'message' => 'Ошибка при удалении',
                'error' => $e->getMessage(),
                'status' => 500
            ];
        }
    }

    private function deleteTask(Tasks $task): void
    {
        $teamIds = $task->solvedTasks()->pluck('teams_id')->unique()->filter()->toArray();

        if (empty($teamIds)) {
            $task->delete();
            return;
        }

        $checkTasks = CheckTasks::whereIn('teams_id', $teamIds)->get();

        foreach ($checkTasks as $checkTask) {
            $checkTask->sumary = max(0, $checkTask->sumary - 1);

            $complexityField = match($task->complexity) {
                'easy' => 'easy',
                'medium' => 'medium',
                'hard' => 'hard',
                default => null
            };

            if ($complexityField && $checkTask->$complexityField > 0) {
                $checkTask->$complexityField -= 1;
            }

            $checkTask->save();
        }

        $task->delete();
    }

    // ----------------------------------------------------------------SETTINGS
    public function settingsReset(Request $request): array
    {
        if($request->input('check') === 'Yes' && $request->input('ButtonReset') === 'RESET'){
            SolvedTasks::truncate();

            CheckTasks::query()->update(['sumary' => 0, 'easy' => 0, 'medium' => 0, 'hard' => 0]);
            Teams::query()->update(['scores' => 0]);

            $tasks = Tasks::all();
            if ($tasks){
                foreach ($tasks as $task) {
                    $task->solved = 0;
                    $task->price = $task->oldprice;
                    $task->save();
                }
            }

            $this->events->adminEvents();
            $this->events->appEvents();



            return [
                'success' => true,
                'message' => 'Сброс прошел успешно!',
                'status' => 200,
            ];
        }

        return [
            'success' => false,
            'message' => 'Сброс настроек не прошел!',
            'status' => 500
        ];
    }

    public function settingsDeleteAll(Request $request): array
    {
        if($request->input('check') === 'Yes' && $request->input('ButtonDeleteAll') === 'DELETEALL'){

            SolvedTasks::truncate();
            CheckTasks::truncate();
            Teams::truncate();
            Tasks::truncate();

            $this->events->adminEvents();
            $this->events->appEvents();

            return [
                'success' => true,
                'message' => 'Удаление настроек прошло успешно!',
                'status' => 200,
            ];
        }

        return [
            'success' => false,
            'message' => 'Удаление настроек не прошло!',
            'status' => 500
        ];
    }

    public function settingsChangeCategory(Request $request): array
    {
        try {
            $action = $request->input('command');
            $categoryName = $request->input('categoryName');
            if ($action && $categoryName) {
                $AllCategories = $this->settings->get('categories');
                if ($action === 'add'){
                    //добавление новой категории
                    if (!in_array($categoryName, $AllCategories)) {
                        $AllCategories[] = $categoryName;
                        $this->settings->set('categories', $AllCategories);

                        return [
                            'success' => true,
                            'message' => 'Категория успешно добавлена!',
                            'categories' => $this->settings->get('categories'),
                            'status' => 200
                        ];
                    }
                }
                if ($action === 'delete') {
                    //удаление категории
                    if (in_array($categoryName, $AllCategories)) {
                        $filteredCategories = array_diff($AllCategories, [$categoryName]);
                        $filteredCategories = array_values($filteredCategories);
                        $this->settings->set('categories', $filteredCategories);
                        $tasks = Tasks::where('category', $categoryName)->get();
                        if ($tasks){
                            foreach ($tasks as $task) {
                                if($task){
                                    $this->DeleteTask($task);
                                }
                                $FILES = $task->FILES;
                                $arrayfiles = explode(";", $FILES);
                                foreach ($arrayfiles as $file){
                                    $deleteFile = 'private/TasksFiles/' . $file;
                                    Storage::delete($deleteFile);
                                }
                            }
                        }
                        $this->updateTeamsScores();
                        $this->events->adminEvents();
                        $this->events->appEvents();

                        return [
                            'success' => true,
                            'message' => 'Категория успешно удалена!',
                            'categories' => $this->settings->get('categories'),
                            'status' => 200
                        ];
                    }
                    else {
                        return [
                            'success' => false,
                            'message' => 'Категория для удаления не найдена!',
                            'categories' => $this->settings->get('categories'),
                            'status' => 404
                        ];
                    }
                }

                return [
                    'success' => false,
                    'message' => 'Ошибка при добавлении категории! Команда не распознана!',
                    'categories' => $this->settings->get('categories'),
                    'status' => 404
                ];
            }

            return [
                'success' => false,
                'message' => 'Ошибка при добавлении категории!',
                'categories' => $this->settings->get('categories'),
                'status' => 500
            ];
        }
        catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Неизвестная ошибка при добавлении категории!' . $e,
                'categories' => $this->settings->get('categories'),
                'status' => 500
            ];
        }
    }

    public function settingsChangeRules(Request $request): array
    {
        // Проверяем условия выполнения
        if ($request->input('check') !== 'Yes' || $request->input('ButtonChangeRull') !== 'CHNGRULL') {
            return [
                'success' => false,
                'message' => 'Не выполнены условия для изменения правил!',
                'status' => 400
            ];
        }

        try {
            $newRules = $request->input('Rull');

            // Проверяем, что новые правила переданы и не пустые
            if (empty($newRules)) {
                return [
                    'success' => false,
                    'message' => 'Текст правил не может быть пустым!',
                    'status' => 400
                ];
            }

            // Обновляем правила в настройках
            $this->settings->set('AppRulesTB', $newRules);

            // Отправляем событие обновления правил
            UpdateRulesEvent::dispatch($newRules);

            return [
                'success' => true,
                'message' => 'Правила успешно обновлены!',
                'rules' => $this->settings->get('AppRulesTB'),
                'status' => 200
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Ошибка при обновлении правил: ' . $e->getMessage(),
                'status' => 500
            ];
        }
    }

    public function settingsSidebars(Request $request): array
    {
        try {
            // Подготавливаем массив для массового обновления
            $sidebarUpdates = [];

            // Список всех возможных пунктов sidebar
            $sidebarItems = [
                'Rules',
                'Projector',
                'Admin',
                'Home',
                'Scoreboard',
                'Statistics',
                'Logout'
            ];

            // Обрабатываем каждый пункт меню
            foreach ($sidebarItems as $item) {
                $inputValue = $request->input($item);
                if ($inputValue === 'yes' || $inputValue === 'no') {
                    // Преобразуем 'yes'/'no' в boolean
                    $sidebarUpdates["sidebar.{$item}"] = $inputValue === 'yes';
                }
            }

            // Если есть что обновлять
            if (!empty($sidebarUpdates)) {
                $this->settings->setMany($sidebarUpdates);

                return [
                    'success' => true,
                    'message' => 'Sidebar успешно обновлен!',
                    'data' => $this->settings->get('sidebar'),
                    'status' => 200
                ];
            }

            if ($request->input('TokenAuth') === 'yes'){
                $auth = 'token';
                $this->settings->set('auth', $auth);

                return [
                    'success' => true,
                    'message' => 'Включена авторизация через токены!',
                    'status' => 200
                ];
            }
            if ($request->input('TokenAuth') === 'no') {
                $auth = 'base';
                $this->settings->set('auth', $auth);

                return [
                    'success' => true,
                    'message' => 'Включена авторизация через логин и пароль!',
                    'status' => 200
                ];
            }

            return [
                'success' => false,
                'message' => 'Нет данных для обновления!',
                'status' => 400
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Ошибка при обновлении sidebar: ' . $e->getMessage(),
                'status' => 500
            ];
        }
    }

    // ----------------------------------------------------------------OTHER
    private function smartReplaceDockerPorts($directory, $webPort, $dbPort): bool
    {
        $composePath = 'private/'.$directory.'/docker-compose.yml';

        // Проверяем существование файла
        if (!Storage::exists($composePath)) {
            return false;
        }

        // Проверяем валидность портов (должны быть либо null, либо числовые)
        $hasValidWebPort = is_numeric($webPort);
        $hasValidDbPort = is_numeric($dbPort);

        // Если оба порта невалидны - выходим
        if (!$hasValidWebPort && !$hasValidDbPort) {
            return false;
        }

        try {
            $content = Storage::get($composePath);
            $yaml = Yaml::parse($content);

            $dbPatterns = [
                '/mysql/i', '/postgres/i', '/mariadb/i', '/redis/i', '/mongo/i',
                '/^db[\W_]/i', '/[\W_]db[\W_]/i', '/[\W_]db$/i',
                '/database/i', '/_sql/i', '/_data/i'
            ];

            $changesMade = false;

            foreach ($yaml['services'] ?? [] as $serviceName => $serviceConfig) {
                if (empty($serviceConfig['ports'])) continue;

                // Определяем тип сервиса (БД или приложение)
                $isDatabase = false;

                // Проверка имени сервиса
                foreach ($dbPatterns as $pattern) {
                    if (preg_match($pattern, $serviceName)) {
                        $isDatabase = true;
                        break;
                    }
                }

                // Проверка образа
                if (!$isDatabase && isset($serviceConfig['image'])) {
                    foreach ($dbPatterns as $pattern) {
                        if (preg_match($pattern, $serviceConfig['image'])) {
                            $isDatabase = true;
                            break;
                        }
                    }
                }

                // Проверка переменных окружения
                if (!$isDatabase && isset($serviceConfig['environment'])) {
                    $env = is_array($serviceConfig['environment'])
                        ? implode(' ', $serviceConfig['environment'])
                        : $serviceConfig['environment'];

                    if (preg_match('/DB_|DATABASE|MYSQL_|POSTGRES_|REDIS_|MONGO_/i', $env)) {
                        $isDatabase = true;
                    }
                }

                // Формируем новые порты
                $newPorts = [];
                foreach ((array)$serviceConfig['ports'] as $portMapping) {
                    if (preg_match('/^"?(\d+):(\d+)/', $portMapping, $matches)) {
                        $externalPort = $matches[1];
                        $internalPort = $matches[2];

                        // Заменяем только если есть валидный порт для этого типа сервиса
                        if ($isDatabase && $hasValidDbPort) {
                            $newPorts[] = "\"{$dbPort}:{$internalPort}\"";
                            $changesMade = true;
                        } elseif (!$isDatabase && $hasValidWebPort) {
                            $newPorts[] = "\"{$webPort}:{$internalPort}\"";
                            $changesMade = true;
                        } else {
                            // Оставляем порт как есть
                            $newPorts[] = "\"{$externalPort}:{$internalPort}\"";
                        }
                    }
                }

                // Заменяем в исходном содержимом
                if (!empty($newPorts)) {
                    $serviceBlock = preg_quote($serviceName, '/');
                    $content = preg_replace(
                        "/({$serviceBlock}:[\s\S]*?ports:)([\s\S]*?)(\n\s+[a-z]|$)/i",
                        "$1\n      - " . implode("\n      - ", $newPorts) . "$3",
                        $content,
                        1
                    );
                }
            }

            // Сохраняем изменения только если они были
            if ($changesMade) {
                Storage::put($composePath, $content);
                return true;
            }

            return false;

        } catch (ParseException $e) {
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function updateTeamsScores(): void
    {
        $userAll = Teams::all();
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

    private function updateTaskPriceDelete(Tasks $task): void
    {
        $countteams = DB::table('users')->count()-1;
        if ($countteams !== 0){
            $rate = $task->solved / $countteams;

            if($rate < 0.2){
                $task->price = $task->oldprice;
            }
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
            $task->save();
        }
        $task->save();
    }

    private function updateTaskPrice(Tasks $task): void
    {
        $countteams = DB::table('users')->count();
        $rate = $task->solved / $countteams;

        if($rate < 0.2){
            $task->price = $task->oldprice;
        }
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
        $task->save();
    }
}

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
use App\Models\desided_tasks_teams;
use App\Models\infoTasks;
use App\Models\Settings;
use App\Models\SolvedTasks;
use App\Models\Tasks;
use App\Models\User;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
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
    // ----------------------------------------------------------------AUTH
    public function AdminAuth(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['max:255'],
            'password' => ['required'],
        ]);
        $remember = $request->has('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Возвращаем JSON с URL для редиректа
            return response()->json([
                'success' => true,
                'redirect_url' => url("/Admin"), // или ->intended() если нужно
            ]);

//            $url = url("/Admin");
//            return redirect()->intended($url);
        }

        return response()->json([
            'success' => false,
            'message' => __('Incorrect credentials'), // Исправлено на "credentials"
        ], 401); // 401 — Unauthorized
    }
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/Admin/Auth');
    }
    // ----------------------------------------------------------------TEAMS
    public function AddTeams(Request $request)
    {
        // Валидация входных данных
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'players' => ['required', 'integer', 'min:1'],
            'WhereFrom' => ['required', 'string'],
            'password' => ['required','string','min:6'],
            'file' => [
                File::image()
                    ->min('1kb')
                    ->max('1mb')
            ]
        ]);

        if ($validator->fails()) {
            $firstErrorMessage = $validator->errors()->first();
            return response()->json(['message' => $firstErrorMessage], 422);
        }

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
        $q = User::all();
        $userId = $this->makeId($q);

        // Создание новой команды
        $team = new User();
        $team->id = $userId;
        $team->name = $sanitizedName;
        $team->password = Hash::make($request->input('password'));
        $team->password_encr = Crypt::encrypt($request->input('password'));
        $team->token = md5($sanitizedName. Str::random(32) . $userId);
        $team->teamlogo = $filename;
        $team->players = $sanitizedPlayers;
        $team->wherefrom = $sanitizedWhereFrom;
        $team->guest = $isGuest;
        $team->scores = 0;
        $team->save();

        // Создание новой записи CheckTasks для команды
        $q = CheckTasks::all();
        $chkId = $this->makeId($q);

        $chktasks = new CheckTasks();
        $chktasks->id = $chkId;
        $chktasks->user_id = $userId;
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
        $this->UpdateTeamsScores();

        // Обновление событий
        $this->AdminEvents();
        $this->AppEvents();

        // Возврат ответа
        return response()->json(['success' => true,'message' => 'Команда успешно добавлена!'], 200);
    }
    // -------------------------------DeleteTeams
    public function DeleteTeams(Request $request)
    {
        try {
            if (is_numeric($request->input('ID'))) {
                $user = User::findOrFail($request->input('ID'));
                $this->deleteUser($user);
                $this->UpdateTeamsScores();
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
                    $user = User::find($id);
                    if ($user) {
                        $this->deleteUser($user);
                        $this->UpdateTeamsScores();
                    }
                }
            }

            $this->AdminEvents();
            $this->AppEvents();

            return response()->json(['success' => true,'message' => 'Команда успешно удалена/удалены!'], 200);
        } catch (\Exception $e) {
            // Обработка ошибки
            return response()->json(['success' => false,'message' => 'Ошибка при удалении!'], 500);
        }
    }
    private function deleteUser(User $user)
    {
        if ($user->teamlogo !== 'StandartLogo.png') {
            $deleteImage = 'public/teamlogo/' . $user->teamlogo;
            Storage::delete($deleteImage);
        }

        $tasks = $user->solvedTasks()->with('tasks')->get();
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

        $user->desidedtasksteams()->delete();
        $user->solvedTasks()->delete();
        $user->checkTasks()->delete();

        $user->delete();
    }
    public function ChangeTeams(Request $request)
    {
        //dd($request->all());
        $TeamID = $request->input('id');
        $team = User::find($TeamID);

        //dd([$sanitizedName, $sanitizedPlayers, $sanitizedWhereFrom]);
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string','max:255'],
            'players' => ['required', 'integer','min:1'],
            'WhereFrom' => ['required','string'],
            'file' => [
                File::image()
                    ->min('1kb')
                    ->max('1mb')
            ]
        ]);


        if ($validator->fails()) {
            $firstErrorMessage = $validator->errors()->first();
            return response()->json(['success' => false,'message' => $firstErrorMessage], 422);
        }

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
                $team->password_encr = Crypt::encrypt($request->input('password'));
                $team->token = md5($sanitizedName. Str::random(32) . $TeamID);
            }

            $team->players = $sanitizedPlayers;
            $team->wherefrom = $sanitizedWhereFrom;
            $team->guest = $IsGuest;
            $team->save();
        }
        else {
            return response()->json(['success' => false,'message' => 'Команда не найдена!'], 200);

        }

        $this->AdminEvents();
        $this->AppEvents();

        return response()->json(['success' => true,'message' => 'Команда успешно Обновлена!'], 200);
    }
    // ----------------------------------------------------------------TASKS

    /**
     * @throws \Exception
     */
    public function AddTasks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string','max:255'],
            'category' => ['required', 'string','max:255'],
            'complexity' => ['required','string','max:255'],
            'points' => ['required','int','min:200'],
            'description' => ['required','string'],
            'flag' => ['required','string'],
            'web_port' => 'nullable|integer|between:1024,65535',
            'db_port' => 'nullable|integer|between:1024,65535',
            'sourcecode' => [
                'nullable',
                'file',
                'mimetypes:application/zip,application/x-zip-compressed',
                'mimes:zip',
                'max:10240' // 10MB максимум
            ]
        ]);

        if ($validator->fails()) {
            $firstErrorMessage = $validator->errors()->first();
            return response()->json(['message' => $firstErrorMessage], 422);
        }

        $sanitizedName = htmlspecialchars($request->input('name'));
        $sanitizedCategory = htmlspecialchars($request->input('category'));
        $sanitizedComplexity = htmlspecialchars($request->input('complexity'));
        $sanitizedDescription = $request->input('description');

        $q = Tasks::all();
        $id = $this->makeId($q);

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
                        return response()->json(['message' => 'Ошибка распаковки архива'], 500);

                    }
                } else {
                    $errorMsg = "Failed to open ZIP archive (code $res): " . $zipPath;
                    Log::error($errorMsg);
                    return response()->json(['message' => 'Ошибка открытия архива'], 500);

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
                    return response()->json(['message' => 'Ошибка распаковки архива'], 500);
                }
            } else {
                $errorMsg = "Failed to open ZIP archive (code $res): " . $zipPath;
                Log::error($errorMsg);
                return response()->json(['message' => 'Ошибка открытия архива'], 500);
            }
            //$this->startDockerContainer($webDirectory);
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

        $this->AdminEvents();
        $this->AppEvents();

        return response()->json(['success' => true,'message' => 'Таск успешно добавлен!'], 200);


//        if ($webDirectory !== null){
//            $this->startDockerContainer($webDirectory);
//        }
    }
    public function ChangeTasks(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'complexity' => ['required', 'string', 'max:255'],
            'points' => ['required', 'int', 'min:200'],
            'description' => ['required', 'string'],
            'flag' => ['required', 'string'],
            'id' => ['required', 'int'],
            'web_port' => 'nullable|integer|between:1024,65535',
            'db_port' => 'nullable|integer|between:1024,65535',
            'sourcecode' => [
                'nullable',
                'file',
                'mimetypes:application/zip,application/x-zip-compressed',
                'mimes:zip',
                'max:10240'
            ]
        ]);

        if ($validator->fails()) {
            $firstErrorMessage = $validator->errors()->first();
            return response()->json(['message' => $firstErrorMessage], 422);
        }

        $sanitizedName = htmlspecialchars($request->input('name'));
        $sanitizedCategory = htmlspecialchars($request->input('category'));
        $sanitizedComplexity = htmlspecialchars($request->input('complexity'));
        $sanitizedDescription = $request->input('description');

        // Массив для хранения выполненных действий
        $actions = [];

        try {
            $task = Tasks::findOrFail($request->input('id'));

            // Обработка веб-приложения и исходного кода
            $webDirectory = $task->web_directory;
            $sourceCodeUpdated = false;
            $dockerRestarted = false;

            if ($request->has('web_port') && $request->input('web_port')) {
                if ($request->file('sourcecode')) {
                    $sourcecode = $request->file('sourcecode');
                    $sourcecode->storeAs($webDirectory, 'source.zip', 'private');

                    $zipPath = storage_path('app/private/'.$webDirectory.'/source.zip');
                    $extractPath = storage_path('app/private/'.$webDirectory);

                    $zip = new ZipArchive;
                    $res = $zip->open($zipPath);
                    if ($res === TRUE) {
                        try {
                            if (!file_exists($extractPath)) {
                                mkdir($extractPath, 0755, true);
                            }

                            if ($zip->extractTo($extractPath)) {
                                $zip->close();
                                unlink($zipPath);
                                $sourceCodeUpdated = true;
                                $actions[] = 'Исходный код обновлен';
                            } else {
                                throw new \Exception("Failed to extract ZIP archive");
                            }
                        } catch (\Exception $e) {
                            $zip->close();
                            Log::error("Error extracting ZIP archive: " . $e->getMessage() . " | Path: " . $zipPath);
                            return response()->json(['message' => 'Ошибка распаковки архива'], 500);
                        }
                    } else {
                        $errorMsg = "Failed to open ZIP archive (code $res): " . $zipPath;
                        Log::error($errorMsg);
                        return response()->json(['message' => 'Ошибка открытия архива'], 500);
                    }
                    $this->smartReplaceDockerPorts($webDirectory, $request->input('web_port'), $request->input('db_port'));
                    $actions[] = 'Порты Docker обновлены! ';
                }

                //$this->restartDockerContainer($webDirectory);
                $dockerRestarted = true;
                //$actions[] = 'Docker контейнер перезапущен';
            }
            elseif ($request->file('sourcecode')) {
                $sourcecode = $request->file('sourcecode');
                $sourcecode->storeAs($webDirectory, 'source.zip', 'private');

                $zipPath = storage_path('app/private/'.$webDirectory.'/source.zip');
                $extractPath = storage_path('app/private/'.$webDirectory);

                $zip = new ZipArchive;
                $res = $zip->open($zipPath);
                if ($res === TRUE) {
                    try {
                        if (!file_exists($extractPath)) {
                            mkdir($extractPath, 0755, true);
                        }

                        if ($zip->extractTo($extractPath)) {
                            $zip->close();
                            unlink($zipPath);
                            $sourceCodeUpdated = true;
                            $actions[] = 'Исходный код обновлен! ';
                        } else {
                            throw new \Exception("Failed to extract ZIP archive");
                        }
                    } catch (\Exception $e) {
                        $zip->close();
                        Log::error("Error extracting ZIP archive: " . $e->getMessage() . " | Path: " . $zipPath);
                        return response()->json(['message' => 'Ошибка распаковки архива'], 500);
                    }
                } else {
                    $errorMsg = "Failed to open ZIP archive (code $res): " . $zipPath;
                    Log::error($errorMsg);
                    return response()->json(['message' => 'Ошибка открытия архива'], 500);
                }
                //$this->restartDockerContainer($webDirectory);
                $dockerRestarted = true;
                //$actions[] = 'Docker контейнер перезапущен';
            }

            // Обновление сложности для решенных задач
            $complexityChanged = false;
            if ($task->complexity != $request->input('complexity')) {
                $complexityChanged = true;
                $actions[] = 'Сложность изменена с ' . $task->complexity . ' на ' . $request->input('complexity') . '! ';
            }

            $desid = $task->desidedtasksteams;
            if ($desid) {
                foreach ($desid as $desidtask) {
                    if ($desidtask) {
                        $oldStyle = $desidtask->StyleTask;
                        $newStyle = '';

                        if ($request->input('complexity') === 'easy') {
                            $newStyle = '<div id="easy" style="background-color: #2ba972; border-radius: 5px; Text-align: center; width: 10px; height: 20px; margin-right: 4px"> <b></b> </div>';
                        } elseif ($request->input('complexity') === 'medium') {
                            $newStyle = '<div id="medium" style="background-color: #0086d3; border-radius: 5px; Text-align: center; width: 10px; height: 20px; margin-right: 4px"> <b></b> </div>';
                        } elseif ($request->input('complexity') === 'hard') {
                            $newStyle = '<div id="hard" style="background-color: #ba074f; border-radius: 5px; Text-align: center; width: 10px; height: 20px; margin-right: 4px"> <b></b> </div>';
                        }

                        if ($oldStyle != $newStyle) {
                            $desidtask->StyleTask = $newStyle;
                            $desidtask->save();
                        }
                    }
                }
            }

            if ($complexityChanged) {
                $ST = $task->SolvedTasks;
                if (!$ST->isEmpty()) {
                    $Uid = $ST->pluck('user_id')->unique()->toArray();

                    foreach ($Uid as $uid) {
                        $user = User::find($uid);
                        if ($user && $user->checkTasks) {
                            $CHKT = $user->checkTasks;

                            $oldComplexity = strtolower($task->complexity);
                            if (isset($CHKT->{$oldComplexity})) {
                                $CHKT->{$oldComplexity} = max(0, $CHKT->{$oldComplexity} - 1);
                            }

                            $newComplexity = strtolower($request->input('complexity'));
                            if (isset($CHKT->{$newComplexity})) {
                                $CHKT->{$newComplexity} += 1;
                            }

                            $CHKT->save();
                        }
                    }
                    $actions[] = 'Статистика пользователей обновлена! ';
                }
            }

            // Обработка файлов
            $filesChanged = false;
            $filenames = $task->FILES;

            if ($request->file('file')) {
                $filesChanged = true;
                $filenames = null;

                if ($task->FILES) {
                    $FILES = $task->FILES;
                    $arrayfiles = explode(";", $FILES);
                    foreach ($arrayfiles as $file) {
                        if ($file) {
                            $deleteFile = 'private/TasksFiles/' . $file;
                            Storage::delete($deleteFile);
                        }
                    }
                    $actions[] = 'Старые файлы удалены! ';
                }

                $file = $request->file('file');
                $Hashfile = md5(time() . '_' . $sanitizedName . '_' . $request->input('id') . 'file');
                foreach ($file as $item) {
                    $filename = md5($Hashfile . '_' . $item->getClientOriginalName()) .'.'. $item->getClientOriginalExtension();
                    $filenames .= $filename . ';';
                    $item->storeAs('TasksFiles', $filename, 'private');
                }
                $actions[] = 'Новые файлы загружены! ';
            }

            if ($request->input('deleteFilesFromTask')) {
                $filesChanged = true;
                if ($task->FILES) {
                    $FILES = $task->FILES;
                    $arrayfiles = explode(";", $FILES);
                    foreach ($arrayfiles as $file) {
                        if ($file) {
                            $deleteFile = 'private/TasksFiles/' . $file;
                            Storage::delete($deleteFile);
                        }
                    }
                    $actions[] = 'Все файлы удалены! ';
                }
                $filenames = null;
            }

            // Основные поля задачи
            $basicFieldsUpdated = false;
            if ($task->name != $sanitizedName) {
                $basicFieldsUpdated = true;
                $actions[] = 'Название обновлено! ';
            }
            if ($task->category != $sanitizedCategory) {
                $basicFieldsUpdated = true;
                $actions[] = 'Категория обновлена! ';
            }
            if ($task->description != $sanitizedDescription) {
                $basicFieldsUpdated = true;
                $actions[] = 'Описание обновлено! ';
            }
            if ($task->oldprice != $request->input('points')) {
                $basicFieldsUpdated = true;
                $actions[] = 'Баллы обновлены! ';
            }
            if ($request->input('flag') && $task->flag != $request->input('flag')) {
                $basicFieldsUpdated = true;
                $actions[] = 'Флаг обновлен! ';
            }
            if ($request->input('web_port') && $task->web_port != $request->input('web_port')) {
                $basicFieldsUpdated = true;
                $actions[] = 'Web порт обновлен! ';
            }
            if ($request->input('db_port') && $task->db_port != $request->input('db_port')) {
                $basicFieldsUpdated = true;
                $actions[] = 'DB порт обновлен! ';
            }

            $task->name = $sanitizedName;
            $task->category = $sanitizedCategory;
            $task->complexity = $sanitizedComplexity;
            $task->description = $sanitizedDescription;
            $task->oldprice = $request->input('points');
            $task->decide = null;
            $task->FILES = $filenames;

            if ($request->input('flag')) {
                $task->flag = $request->input('flag');
            }
            if ($request->input('web_port')) {
                $task->web_port = $request->input('web_port');
            }
            if ($request->input('db_port')) {
                $task->db_port = $request->input('db_port');
            }

            $task->web_directory = $webDirectory;
            $task->save();

            $this->AdminEvents();
            $this->AppEvents();

            // Если не было конкретных действий, но задача обновлена
            if (empty($actions)) {
                $actions[] = 'Настройки задачи сохранены (без изменений)';
            }
            $message = implode(' ', $actions);

            return response()->json([
                'success' => true,
                'message' => 'Задача успешно обновлена!',
                'actions' => $actions
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ошибка при обновлении задачи',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    public function DeleteTasks(Request $request)
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
            $this->UpdateTeamsScores();
            $this->AdminEvents();
            $this->AppEvents();
            return response()->json(['success' => true,'message' => 'Таск/Таски удалены!'], 200);

        } catch (\Exception $e) {
            // Обработка ошибки
            return response()->json(['success' => false,'message' => 'Ошибка при удалении'], 500);
        }
    }
    private function DeleteTask(Tasks $task)
    {
        // Остановка веб-контейнера, если он есть
        if ($task->web_directory) {
            /*
            $command = "cd ".storage_path('app/private/'.$task->web_directory)." && docker-compose down";
            shell_exec($command);
            */

            // Удаление директории
            Storage::disk('private')->deleteDirectory($task->web_directory);
        }

        $task->desidedtasksteams()->delete();

        $users = $task->solvedTasks()->with('user')->get();
        $UsersID = [];
        if ($users) {
            foreach ($users as $user) {
                $UsersID[] = $user->user->id;
            }
            foreach ($UsersID as $ID) {
                $user = User::find($ID);
                $CHKTforUser = $user->checkTasks;
                if ($CHKTforUser) {
                    $CHKTforUser->sumary--;
                    if ($task->complexity === 'easy') {
                        $CHKTforUser->easy -= 1;
                    }
                    if ($task->complexity === 'medium') {
                        $CHKTforUser->medium -= 1;
                    }
                    if ($task->complexity === 'hard') {
                        $CHKTforUser->hard -= 1;
                    }
                    $CHKTforUser->save();
                }
            }
        }
        $task->solvedTasks()->delete();
        $task->delete();
    }

    // ----------------------------------------------------------------SETTINGS
    public function SettingsReset(Request $request)
    {
        if($request->input('check') === 'Yes' && $request->input('ButtonReset') === 'RESET'){
            desided_tasks_teams::truncate();
            SolvedTasks::truncate();

            CheckTasks::query()->update(['sumary' => 0, 'easy' => 0, 'medium' => 0, 'hard' => 0]);
            User::query()->update(['scores' => 0]);

            $tasks = Tasks::all();
            if ($tasks){
                foreach ($tasks as $task) {
                    $task->solved = 0;
                    $task->price = $task->oldprice;
                    $task->save();
                }
            }

            $this->AdminEvents();
            $this->AppEvents();

            return response()->json([
                'success' => true,
                'message' => 'Сброс прошел успешно!'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Сброс настроек не прошел!'
        ], 500);
    }
    public function SettingsDeleteAll(Request $request)
    {
        if($request->input('check') === 'Yes' && $request->input('ButtonDeleteAll') === 'DELETEALL'){

            desided_tasks_teams::truncate();
            SolvedTasks::truncate();
            CheckTasks::truncate();
            User::truncate();
            Tasks::truncate();
            $infotasks = infoTasks::find(1);
            if($infotasks){
                $infotasks->id = 0;
                $infotasks->sumary = 0;
                $infotasks->easy = 0;
                $infotasks->medium = 0;
                $infotasks->hard = 0;
                $infotasks->admin = 0;
                $infotasks->recon = 0;
                $infotasks->crypto = 0;
                $infotasks->stegano = 0;
                $infotasks->ppc = 0;
                $infotasks->pwn = 0;
                $infotasks->web = 0;
                $infotasks->forensic = 0;
                $infotasks->joy = 0;
                $infotasks->misc = 0;
                $infotasks->save();
            }
            $this->AdminEvents();
            $this->AppEvents();

            return response()->json([
                'success' => true,
                'message' => 'Удаление настроек прошло успешно!'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Удаление настроек не прошло!'
        ], 500);
    }
    public function SettingsChngRulesOLD(Request $request)
    {
        $Rull = $request->input('Rull');
        if($request->input('check') === 'Yes' && $request->input('ButtonChangeRull') === 'CHNGRULL'){
            //dd($request->input('Rull'));
            $settings = Settings::find(1);
            if($settings){
                if ($request->input('Rull')) {
                    $settings->Rule = $Rull;
                }
                $settings->save();
            }
            UpdateRulesEvent::dispatch($Rull);
            return response()->json([
                'success' => true,
                'message' => 'Правила успешно обновлены!'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Не удалось обновить правила!'
        ], 500);
    }
    public function SettingsChgCategory(Request $request, SettingsService $settings)
    {
        try {
            $action = $request->input('command');
            $categoryName = $request->input('categoryName');
            if ($action && $categoryName) {
                $AllCategories = $settings->get('categories');
                if ($action === 'add'){
                    //добавление новой категории
                    if (!in_array($categoryName, $AllCategories)) {
                        $AllCategories[] = $categoryName;
                        $settings->set('categories', $AllCategories);
                        return response()->json(['success' => true,'message' => 'Категория успешно добавлена!','categories' => $AllCategories], 200);
                    }
                }
                if ($action === 'delete') {
                    //удаление категории
                    if (in_array($categoryName, $AllCategories)) {
                        $filteredCategories = array_diff($AllCategories, [$categoryName]);
                        $filteredCategories = array_values($filteredCategories);
                        $settings->set('categories', $filteredCategories);
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
                        $this->UpdateTeamsScores();
                        $this->AdminEvents();
                        $this->AppEvents();
                        return response()->json(['success' => true,'message' => 'Категория успешно удалена!','categories' => $filteredCategories], 200);
                    }
                }
                return response()->json(['success' => false,'message' => 'Ошибка при добавлении категории! Команда не распознана!'], 500);
            }
            return response()->json(['success' => false,'message' => 'Ошибка при добавлении категории!'], 500);
        }
        catch (\Exception $e) {
            return response()->json(['success' => false,'message' => 'Неизвестная ошибка при добавлении категории!'], 500);
        }

    }
    public function SettingsChngRules(Request $request, SettingsService $settings)
    {
        // Проверяем условия выполнения
        if ($request->input('check') !== 'Yes' || $request->input('ButtonChangeRull') !== 'CHNGRULL') {
            return response()->json([
                'success' => false,
                'message' => 'Не выполнены условия для изменения правил!'
            ], 400);
        }

        try {
            $newRules = $request->input('Rull');

            // Проверяем, что новые правила переданы и не пустые
            if (empty($newRules)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Текст правил не может быть пустым!'
                ], 400);
            }

            // Обновляем правила в настройках
            $settings->set('AppRulesTB', $newRules);

            // Отправляем событие обновления правил
            UpdateRulesEvent::dispatch($newRules);

            return response()->json([
                'success' => true,
                'message' => 'Правила успешно обновлены!',
                'rules' => $settings->get('AppRulesTB')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении правил: ' . $e->getMessage()
            ], 500);
        }
    }
    public function SettingsSlidebarsOLD(Request $request)
    {
        //dd($request);
        $settings = Settings::find(1);
        if ($settings){
            //-------------------------------------------------Rules
            if ($request->input('Rules') === 'yes'){
                $settings->Rules = 'yes';
            }
            if ($request->input('Rules') === 'no'){
                $settings->Rules = 'no';
            }
            //-------------------------------------------------Projector
            if ($request->input('Projector') === 'yes'){
                $settings->Projector = 'yes';
            }
            if ($request->input('Projector') === 'no'){
                $settings->Projector = 'no';
            }
            //-------------------------------------------------Admin
            if ($request->input('Admin') === 'yes'){
                $settings->Admin = 'yes';
            }
            if ($request->input('Admin') === 'no'){
                $settings->Admin = 'no';
            }
            //-------------------------------------------------Home
            if ($request->input('Home') === 'yes'){
                $settings->Home = 'yes';
            }
            if ($request->input('Home') === 'no'){
                $settings->Home = 'no';
            }
            //-------------------------------------------------Scoreboard
            if ($request->input('Scoreboard') === 'yes'){
                $settings->Scoreboard = 'yes';
            }
            if ($request->input('Scoreboard') === 'no'){
                $settings->Scoreboard = 'no';
            }
            //-------------------------------------------------Statistics
            if ($request->input('Statistics') === 'yes'){
                $settings->Statistics = 'yes';
            }
            if ($request->input('Statistics') === 'no'){
                $settings->Statistics = 'no';
            }
            //-------------------------------------------------Logout
            if ($request->input('Logout') === 'yes'){
                $settings->Logout = 'yes';
            }
            if ($request->input('Logout') === 'no'){
                $settings->Logout = 'no';
            }

            $T = $settings->save();
            if ($T){
                return response()->json([
                    'success' => true,
                    'message' => 'Sidebar успешно обновлен!'
                ]);
            }
            else {
                return response()->json([
                    'success' => false,
                    'message' => 'Не удалось обновить sidebar!'
                ], 500);
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'Не удалось обновить sidebar!'
        ], 500);
    }
    public function SettingsSlidebars(Request $request, SettingsService $settings)
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
                $settings->setMany($sidebarUpdates);

                return response()->json([
                    'success' => true,
                    'message' => 'Sidebar успешно обновлен!',
                    'data' => $settings->get('sidebar')
                ]);
            }

            if ($request->input('TokenAuth') === 'yes'){
                $auth = 'token';
                $settings->set('auth', $auth);
                return response()->json([
                    'success' => true,
                    'message' => 'Включена авторизация через токены!',
                ], 200);
            }
            if ($request->input('TokenAuth') === 'no') {
                $auth = 'base';
                $settings->set('auth', $auth);
                return response()->json([
                    'success' => true,
                    'message' => 'Включена авторизация через логин и пароль!',
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Нет данных для обновления!'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении sidebar: ' . $e->getMessage()
            ], 500);
        }
    }
    // ----------------------------------------------------------------EVENTS
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

        AdminTasksEvent::dispatch($Tasks);
        AdminTeamsEvent::dispatch($Teams);
        AdminHomeEvent::dispatch($data);
        AdminScoreboardEvent::dispatch($data3);
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
        AppStatisticIDEvent::dispatch($data);
        AppHomeEvent::dispatch($data2);
        AppScoreboardEvent::dispatch($data3);
        AppStatisticEvent::dispatch($Team);
        ProjectorEvent::dispatch($data3);
    }
    // ----------------------------------------------------------------OTHER
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


    private function startDockerContainer($directory)
    {
        $path = storage_path('app/private/'.$directory);

        // 1. Проверяем существование docker-compose.yml
        if (!file_exists($path.'/docker-compose.yml')) {
            throw new \Exception("docker-compose.yml not found in directory: {$path}");
        }

        // 2. Выполняем с таймаутом и перенаправлением stderr в stdout
        $command = "cd {$path} && docker-compose up -d --build 2>&1";
        exec($command, $output, $returnCode);

        // 3. Анализируем результат
        $outputString = implode("\n", $output);

        if ($returnCode !== 0) {
            // Логируем полную информацию для диагностики
            Log::error("Docker command failed", [
                'command' => $command,
                'return_code' => $returnCode,
                'output' => $outputString,
                'directory' => $directory,
                'path' => $path
            ]);

            // Проверяем распространенные проблемы
            if (str_contains($outputString, 'permission denied')) {
                throw new \Exception("Permission denied. Try running with sudo or check directory permissions.");
            }

            if (str_contains($outputString, 'no such file or directory')) {
                throw new \Exception("Required files not found. Check your docker-compose.yml configuration.");
            }

            if (str_contains($outputString, 'port is already allocated')) {
                throw new \Exception("Port conflict. The required port is already in use.");
            }

            throw new \Exception("Failed to start container. Docker output: ".$outputString);
        }

        // 4. Дополнительная проверка что контейнеры действительно запущены
        $running = false;
        $attempts = 0;
        $maxAttempts = 5;

        while (!$running && $attempts < $maxAttempts) {
            sleep(2); // Даем время на запуск
            exec("cd {$path} && docker-compose ps --services", $services, $servicesReturn);

            if ($servicesReturn === 0 && !empty($services)) {
                $running = true;
                foreach ($services as $service) {
                    exec("cd {$path} && docker-compose ps -q {$service} | xargs docker inspect -f '{{.State.Status}}'", $status, $statusReturn);
                    if ($statusReturn !== 0 || (isset($status[0]) && $status[0] !== 'running')) {
                        $running = false;
                        break;
                    }
                }
            }

            $attempts++;
        }

        if (!$running) {
            // Получаем подробную информацию о состоянии
            exec("cd {$path} && docker-compose ps", $psOutput, $psReturn);
            $psInfo = implode("\n", $psOutput);

            Log::error("Containers failed to reach running state", [
                'ps_output' => $psInfo,
                'attempts' => $attempts
            ]);

            throw new \Exception("Containers failed to start properly. Current state:\n".$psInfo);
        }

        return true;
    }
    private function restartDockerContainer($directory)
    {
        $path = storage_path('app/private/'.$directory);
        exec("cd {$path} && docker-compose down && docker-compose up -d --build 2>&1", $output, $return);

        if ($return !== 0) {
            Log::error("Docker error: ".implode("\n", $output));
            throw new \Exception("Failed to restart container");
        }
    }
    private function stopDockerContainer($directory)
    {
        $path = storage_path('app/private/'.$directory);
        exec("cd {$path} && docker-compose down 2>&1", $output, $return);

        if ($return !== 0) {
            Log::error("Docker error: ".implode("\n", $output));
            throw new \Exception("Failed to stop container");
        }
    }
    function smartReplaceDockerPorts($directory, $webPort, $dbPort)
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
    private function generateDockerCompose($directory, $SourseZipname, $webPort, $dbPort = null)
    {
        $composePath = storage_path('app/private/'.$directory.'/docker-compose.yml');

        try {
            $q = Tasks::all();
            $id = $this->makeId($q);
            if (file_exists($composePath)) {
                $config = Yaml::parseFile($composePath);
            } else {
                $config = [
                    'version' => '3',
                    'services' => [
                        "web-{$id}" => [
                            "container_name"=> "web-task-{$id}",
                            'build' => "'./{$SourseZipname}'",
                            'ports' => ["{$webPort}:80"]
                        ]
                    ]
                ];
            }

            // Обновляем порты
            //$config['services']['web']['ports'][0] = "{$webPort}:80";

            if ($dbPort) {
                $config['services']['db'] = [
                    'image' => 'mysql:5.7',
                    'environment' => [
                        'MYSQL_ROOT_PASSWORD' => 'example',
                        'MYSQL_DATABASE' => 'taskdb'
                    ],
                    'ports' => ["{$dbPort}:3306"]
                ];
                $config['services']['web']['depends_on'] = ['db'];
            } elseif (isset($config['services']['db'])) {
                unset($config['services']['db']);
            }

            file_put_contents($composePath, Yaml::dump($config, 4, 2));

        } catch (ParseException $e) {
            Log::error("YAML parse error: ".$e->getMessage());
            throw new \Exception("Invalid docker-compose.yml format");
        }
    }
    public function UpdateTeamsScores()
    {
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
    private function updateTaskPriceDelete(Tasks $task)
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
    private function updateTaskPrice(Tasks $task)
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


    // ----------------------------------------------------------------VIEW
    public function TaskID(int $id)
    {
        $task = Tasks::find($id);
        if($task) {

            $data = compact('id', 'task');
            return view('Admin.AdminTasksID', compact('data'));
        }
        else {
            return redirect()->back()->with('error', 'Задача не найдена');
        }
    }
    public function TeamsID(int $id)
    {
        $team = User::find($id);
        if($team) {
            $data = compact('id', 'team');
            return view('Admin.AdminTeamsID', compact('data'));
        }
        else {
            return redirect()->back()->with('error', 'Команда не найдена');
        }
    }

    public function AdminScoreboardView()
    {
        $Users = User::all();
        $DesidedT = desided_tasks_teams::all();
        return view('Admin.AdminScoreboard', compact('Users', 'DesidedT'));
    }
    public function AdminTeamsView()
    {
        $Teams = User::all()->makeVisible('token');
        return view('Admin.AdminTeams', compact('Teams'));
    }
    public function AdminTasksView(SettingsService $settings)
    {
        $Tasks = Tasks::all()->makeVisible('flag');
        $universalResult = $this->processTasksUniversal($Tasks);

        // Получаем все категории (исключая difficulty и sumary)
        $categories = $universalResult['categories'] ?? [];
        ksort($categories);

        // Получаем все возможные сложности
        $complexities = $universalResult['difficulty'] ?? [];
        ksort($complexities);

        $AllComplexities = $settings->get('complexity');
        $AllCategories = $settings->get('categories');



        return view('Admin.AdminTasks', [
            'Tasks' => $Tasks,
            'categories' => $categories,
            'complexities' => $complexities,
            'infoTasks' => $this->formatToLegacyUniversal($universalResult),
            'AllComplexities' => $AllComplexities,
            'AllCategories' => $AllCategories,
        ]);
    }
    public function AdminSettingsView(SettingsService $settings)
    {
        $Rules = $settings->get('AppRulesTB') ?? '(•ิ_•ิ)?';
        $SettSidebar = $settings->get('sidebar');
        $TypeAuth = $settings->get('auth');
        return view('Admin.AdminSettings', compact('Rules', 'SettSidebar', 'TypeAuth'));
    }
    public function AdminHomeView()
    {
        $Teams = User::all();
        $Tasks = Tasks::all();
        $universalResult = $this->processTasksUniversal($Tasks);
        $InfoTasks = $this->formatToLegacyUniversal($universalResult);
        $CheckTasks = CheckTasks::all();
        $data = [$Tasks, $Teams, $InfoTasks, $CheckTasks];
        return view('Admin.AdminHome', compact('data'));
    }
    public function AdminAuthView()
    {
        if (Auth::guard('admin')->check()) {
            // Пользователь вошел в систему...
            return redirect('/Admin');
        }
        return view('Admin.AdminAuth');
    }
}

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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;

class AdminController extends Controller
{
    // ----------------------------------------------------------------AUTH
    public function AdminAuth(Request $request)
    {
        //dd($request);
        $credentials = $request->validate([
            'name' => ['max:255'],
            'password' => ['required'],
        ]);
        $remember = $request->has('remember');

        //dd($credentials);
        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            // Authentication passed...
            $request->session()->regenerate();
            $url = url("/Admin");
            return redirect()->intended($url);
        }

        return redirect()->back()->withErrors(['No-No-No']);
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
            'token' => ['required','string','min:6'],
            'file' => [
                File::image()
                    ->min('1kb')
                    ->max('1mb')
            ]
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
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
        $team->password = Hash::make($request->input('token'));
        $team->teamlogo = $filename;
        $team->players = $sanitizedPlayers;
        $team->wherefrom = $sanitizedWhereFrom;
        $team->guest = $isGuest;
        $team->GuestLogo = '';
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
        return redirect()->back();
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

            return response()->json(['message' => 'Команды успешно удалены'], 200);
        } catch (\Exception $e) {
            // Обработка ошибки
            return response()->json(['error' => 'Ошибка при удалении: ' ], 500);
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
            return redirect()->back()->withErrors($validator->errors());
        }
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
        if($request->input('token') !== null){
            $team->password = Hash::make($request->input('token'));
        }

        $team->players = $sanitizedPlayers;
        $team->wherefrom = $sanitizedWhereFrom;
        $team->guest = $IsGuest;
        $team->save();


        $this->AdminEvents();
        $this->AppEvents();

        return redirect()->back();
    }
    // ----------------------------------------------------------------TASKS
    public function AddTasks(Request $request)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string','max:255'],
            'category' => ['required', 'string','max:255'],
            'complexity' => ['required','string','max:255'],
            'points' => ['required','int','min:200'],
            'description' => ['required','string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
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
            //dd($file);
            if ($file) {
                $Hashfile = md5(time() . '_' . $sanitizedName . '_' . $id . 'file');
                foreach ($file as $item) {
                    $filename = md5($Hashfile . '_' . $item->getClientOriginalName()) .'.'. $item->getClientOriginalExtension();
                    $filenames .= $filename . ';';
                    $item->storeAs('TasksFiles', $filename, 'private');
                }
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
        $task->solved = 0;
        $task->save();

        $infotasks = infoTasks::find(1);

        if ($infotasks) {
            $infotasks->sumary += 1;

            //complexity
            if ($request->input('complexity') === 'easy'){
                $infotasks->easy += 1;
            }
            if ($request->input('complexity') === 'medium'){
                $infotasks->medium += 1;
            }
            if ($request->input('complexity') === 'hard'){
                $infotasks->hard += 1;
            }

            //category
            if ($request->input('category') === 'admin'){
                $infotasks->admin += 1;
            }
            if ($request->input('category') ==='recon'){
                $infotasks->recon += 1;
            }
            if ($request->input('category') === 'crypto'){
                $infotasks->crypto += 1;
            }
            if ($request->input('category') === 'stegano'){
                $infotasks->stegano += 1;
            }
            if ($request->input('category') ==='ppc'){
                $infotasks->ppc += 1;
            }
            if ($request->input('category') === 'pwn'){
                $infotasks->pwn += 1;
            }
            if ($request->input('category') === 'web'){
                $infotasks->web += 1;
            }
            if ($request->input('category') ==='forensic'){
                $infotasks->forensic += 1;
            }
            if ($request->input('category') === 'joy'){
                $infotasks->joy += 1;
            }
            if ($request->input('category') === 'misc'){
                $infotasks->misc += 1;
            }
            if ($request->input('category') === 'osint'){
                $infotasks->osint += 1;
            }
            if ($request->input('category') === 'reverse'){
                $infotasks->reverse += 1;
            }

            $infotasks->save();
        }

        $this->AdminEvents();
        $this->AppEvents();
    }
    // -------------------------------DeleteTasks
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
            return response()->json(['message' => 'Задачи удалены'], 200);

        } catch (\Exception $e) {
            // Обработка ошибки
            return response()->json(['error' => 'Ошибка при удалении ' . $e], 500);
        }
    }
    private function DeleteTask(Tasks $task)
    {
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
        $infotasks = infoTasks::find(1);
        if ($infotasks){
            $infotasks->sumary -= 1;

            //complexity
            if ($task->complexity === 'easy') {
                $infotasks->easy -= 1;
            }
            if ($task->complexity === 'medium') {
                $infotasks->medium -= 1;
            }
            if ($task->complexity === 'hard') {
                $infotasks->hard -= 1;
            }

            //category
            if ($task->category === 'admin') {
                $infotasks->admin -= 1;
            }
            if ($task->category === 'recon') {
                $infotasks->recon -= 1;
            }
            if ($task->category === 'crypto') {
                $infotasks->crypto -= 1;
            }
            if ($task->category === 'stegano') {
                $infotasks->stegano -= 1;
            }
            if ($task->category === 'ppc') {
                $infotasks->ppc -= 1;
            }
            if ($task->category === 'pwn') {
                $infotasks->pwn -= 1;
            }
            if ($task->category === 'web') {
                $infotasks->web -= 1;
            }
            if ($task->category === 'forensic') {
                $infotasks->forensic -= 1;
            }
            if ($task->category === 'joy') {
                $infotasks->joy -= 1;
            }
            if ($task->category === 'misc') {
                $infotasks->misc -= 1;
            }
            if ($task->category === 'osint') {
                $infotasks->osint -= 1;
            }
            if ($task->category === 'reverse') {
                $infotasks->reverse -= 1;
            }


            $infotasks->save();
        }
        $task->delete();
    }
    public function ChangeTasks(Request $request){
        //dd($request->all());

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string','max:255'],
            'category' => ['required', 'string','max:255'],
            'complexity' => ['required','string','max:255'],
            'points' => ['required','int','min:200'],
            'description' => ['required','string'],
            'id' => ['required','int'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $sanitizedName = htmlspecialchars($request->input('name'));
        $sanitizedCategory = htmlspecialchars($request->input('category'));
        $sanitizedComplexity = htmlspecialchars($request->input('complexity'));
        $sanitizedDescription = $request->input('description');

        try {
            $task = Tasks::findOrFail($request->input('id'));
            $desid = $task->desidedtasksteams;
            if ($desid){
                foreach ($desid as $desidtask){
                    if ($desidtask){
                        if ($request->input('complexity') === 'easy'){
                            $desidtask->StyleTask = '<div id="easy" style="background-color: #2ba972; border-radius: 5px; Text-align: center; width: 10px; height: 20px; margin-right: 4px"> <b></b> </div>';
                        }
                        if ($request->input('complexity') ==='medium'){
                            $desidtask->StyleTask = '<div id="medium" style="background-color: #0086d3; border-radius: 5px; Text-align: center; width: 10px; height: 20px; margin-right: 4px"> <b></b> </div>';
                        }
                        if ($request->input('complexity') === 'hard'){
                            $desidtask->StyleTask = '<div id="hard" style="background-color: #ba074f; border-radius: 5px; Text-align: center; width: 10px; height: 20px; margin-right: 4px"> <b></b> </div>';
                        }
                        $desidtask->save();
                    }
                }
            }
            $infotasks = infoTasks::find(1);

            //complexity
            if ($task->complexity === 'easy'){
                $infotasks->easy -= 1;
            }
            if ($task->complexity ==='medium'){
                $infotasks->medium -= 1;
            }
            if ($task->complexity === 'hard'){
                $infotasks->hard -= 1;
            }

            //category
            if ($task->category === 'admin'){
                $infotasks->admin -= 1;
            }
            if ($task->category ==='recon'){
                $infotasks->recon -= 1;
            }
            if ($task->category === 'crypto'){
                $infotasks->crypto -= 1;
            }
            if ($task->category ==='stegano'){
                $infotasks->stegano -= 1;
            }
            if ($task->category ==='ppc'){
                $infotasks->ppc -= 1;
            }
            if ($task->category === 'pwn'){
                $infotasks->pwn -= 1;
            }
            if ($task->category === 'web'){
                $infotasks->web -= 1;
            }
            if ($task->category ==='forensic'){
                $infotasks->forensic -= 1;
            }
            if ($task->category === 'joy'){
                $infotasks->joy -= 1;
            }
            if ($task->category === 'misc'){
                $infotasks->misc -= 1;
            }

            if ($infotasks) {

                //complexity
                if ($request->input('complexity') === 'easy'){
                    $infotasks->easy += 1;
                }
                if ($request->input('complexity') ==='medium'){
                    $infotasks->medium += 1;
                }
                if ($request->input('complexity') === 'hard'){
                    $infotasks->hard += 1;
                }

                //category
                if ($request->input('category') === 'admin'){
                    $infotasks->admin += 1;
                }
                if ($request->input('category') ==='recon'){
                    $infotasks->recon += 1;
                }
                if ($request->input('category') === 'crypto'){
                    $infotasks->crypto += 1;
                }
                if ($request->input('category') === 'stegano'){
                    $infotasks->stegano += 1;
                }
                if ($request->input('category') ==='ppc'){
                    $infotasks->ppc += 1;
                }
                if ($request->input('category') === 'pwn'){
                    $infotasks->pwn += 1;
                }
                if ($request->input('category') === 'web'){
                    $infotasks->web += 1;
                }
                if ($request->input('category') ==='forensic'){
                    $infotasks->forensic += 1;
                }
                if ($request->input('category') === 'joy'){
                    $infotasks->joy += 1;
                }
                if ($request->input('category') === 'misc'){
                    $infotasks->misc += 1;
                }

                $infotasks->save();
            }

            $ST = $task->SolvedTasks;
            if($ST){
                $Uid = [];
                foreach ($ST as $U){
                    $Uid[] = $U->user_id;
                }
                foreach ($Uid as $uid){
                    $user = User::find($uid);
                    if ($user){
                        $CHKT = $user->checkTasks;
                        if($CHKT){
                            if ($task->complexity === 'easy'){
                                $CHKT->easy -= 1;
                            }
                            if ($task->complexity ==='medium'){
                                $CHKT->medium -= 1;
                            }
                            if ($task->complexity === 'hard'){
                                $CHKT->hard -= 1;
                            }

                            if ($request->input('complexity') === 'easy'){
                                $CHKT->easy += 1;
                            }
                            if ($request->input('complexity') ==='medium'){
                                $CHKT->medium += 1;
                            }
                            if ($request->input('complexity') === 'hard'){
                                $CHKT->hard += 1;
                            }
                            $CHKT->save();
                        }
                    }
                }
            }
            //dd($request->file('file'));
            $filenames = null;
            if ($task->FILES) {
                $filenames = $task->FILES;
            }
            if ($request->file('file')){
                $filenames = null;
                if ($task->FILES) {
                    $FILES = $task->FILES;
                    $arrayfiles = explode(";", $FILES);
                    foreach ($arrayfiles as $file){
                        if($file){
                            $deleteFile = 'private/TasksFiles/' . $file;
                            Storage::delete($deleteFile);
                        }
                    }
                    $file = $request->file('file');
                    //dd($file);
                    //dd($file);

                    $Hashfile = md5(time() . '_' . $sanitizedName . '_' . $request->input('id') . 'file');
                    foreach ($file as $item) {
                        $filename = md5($Hashfile . '_' . $item->getClientOriginalName()) .'.'. $item->getClientOriginalExtension();
                        $filenames .= $filename . ';';
                        $item->storeAs('TasksFiles', $filename, 'private');
                    }

                }
                else {
                    $file = $request->file('file');
                    //dd($file);
                    if ($file) {
                        $Hashfile = md5(time() . '_' . $sanitizedName . '_' . $request->input('id') . 'file');
                        foreach ($file as $item) {
                            $filename = md5($Hashfile . '_' . $item->getClientOriginalName()) .'.'. $item->getClientOriginalExtension();
                            $filenames .= $filename . ';';
                            $item->storeAs('TasksFiles', $filename, 'private');
                        }
                    }
                }
            }
            if($request->input('deleteFilesFromTask')){
                if ($task->FILES) {
                    $FILES = $task->FILES;
                    $arrayfiles = explode(";", $FILES);
                    foreach ($arrayfiles as $file) {
                        if ($file) {
                            $deleteFile = 'private/TasksFiles/' . $file;
                            Storage::delete($deleteFile);
                        }
                    }
                }
                $filenames = null;
            }

            $task->name = $sanitizedName;
            $task->category = $sanitizedCategory;
            $task->complexity = $sanitizedComplexity;
            $task->description = $sanitizedDescription;
            $task->oldprice = $request->input('points');
            $task->decide = null;
            $task->FILES = $filenames;
            $task->flag = $request->input('flag');

            $task->save();

            $this->AdminEvents();
            $this->AppEvents();

        } catch (\Exception $e) {
            // Обработка ошибки
            return response()->json(['error' => 'Ошибка при удалении ' . $e], 500);
        }
        return redirect()->back();
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
    public function SettingsChngRules(Request $request)
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
    public function SettingsSlidebars(Request $request)
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
    }
    // ----------------------------------------------------------------EVENTS
    public function AdminEvents()
    {
        $Teams = User::all();
        $Tasks = Tasks::all();
        $InfoTasks = infoTasks::all();
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
        $M = User::all();
        return view('Admin.AdminScoreboard', compact('M'));
    }
    public function AdminTeamsView()
    {
        return view('Admin.AdminTeams');
    }
    public function AdminTasksView()
    {
        return view('Admin.AdminTasks');
    }
    public function AdminSettingsView()
    {
        $Sett = Settings::find(1)->Rule;
        return view('Admin.AdminSettings', compact('Sett'));
    }
    public function AdminHomeView()
    {
        return view('Admin.AdminHome');
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

<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use App\Models\Tasks;
use App\Models\User;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class AppController extends Controller
{
    //----------------------------------------------------------------Auth
    public function AuthTeam(Request $request, SettingsService $settings)
    {
        $auth = $settings->get('auth');
        $credentials = [];
        if ($auth === 'base') {
            $credentials = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'password' => ['required'],
            ]);
        }
        if ($auth === 'token') {
            $user = User::where('token', $request->token)->first();
            //dd($request);

            $credentials = [];
            if ($user){
                $credentials = ['name' => $user->name, 'password' => Crypt::decrypt($user->password_encr)];
            }
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Возвращаем JSON с URL для редиректа
            return response()->json([
                'success' => true,
                'redirect_url' => url("/Home"), // или ->intended() если нужно
            ]);

//            $url = url("/Home");
//            return redirect()->intended($url);
        }

        if ($auth === 'base') {
            return response()->json(['success' => false,'message' => __('Incorrect username or password')], 401);
        }
        if ($auth === 'token') {
            return response()->json(['success' => false,'message' => __('Incorrect token')], 401);
        }

        return response()->json(['success' => false,'message' => __('Auth error')], 500);
    }
    public function logout(Request $request, SettingsService $settings)
    {
        if(!$settings->get('sidebar.Logout')){
            abort(403);
        }
        // Выход текущего пользователя из системы
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/Auth');
    }

    //----------------------------------------------------------------View
    public function StatisticIDview(int $id, SettingsService $settings)
    {
        $user = \App\Models\User::with('solvedTasks.tasks')->find($id);
        if(!$settings->get('sidebar.Statistics')){
            abort(403);
        }
        if (!$user) {
            return redirect()->back()->with('error', 'Пользователь не найден');
        }
        else {
            $i = 0;
            $TeamSolvedTAsks = [];
            foreach ($user->solvedTasks as $solvedTask) {
                $TeamSolvedTAsks[$i] = $solvedTask->tasks;
                $i++;
                //dd($solvedTask->tasks); // Допустим, у вас есть поле name в таблице задач
            }
            $M = \App\Models\User::all();
            for ($i = 0; $i < count($M); $i++) {
                $M[$i]->teamlogo = asset('storage/teamlogo/' . $M[$i]->teamlogo);
            }
            $chkT = \App\Models\User::find($id)->checkTasks;
            return view('App.AppStatisticID', compact('id', 'M', 'chkT', 'TeamSolvedTAsks'));
        }
    }
    public function StatisticView(SettingsService $settings)
    {
        if(!$settings->get('sidebar.Statistics')){
            abort(403);
        }
        $M = \App\Models\User::all();
        return view('App.AppStatistic', compact('M'));
    }
    public function ScoreboardView(SettingsService $settings)
    {
        if(!$settings->get('sidebar.Scoreboard')){
            abort(403);
        }
        $M = \App\Models\User::all();
        return view('App.AppScoreboard', compact('M'));
    }
    public function ProjectorView(SettingsService $settings)
    {
        if(!$settings->get('sidebar.Projector')){
            abort(403);
        }
        $M = \App\Models\User::all();
        return view('Guest.projectorTB', compact('M'));
    }
    public function HomeView(SettingsService $settings)
    {
        if(!$settings->get('sidebar.Home')){
            abort(403);
        }
        $Tasks = Tasks::all();
        $SolvedTasks = User::find(auth()->id())->solvedTasks;
        $universalResult = $this->processTasksUniversal($Tasks);

        // Получаем все категории (исключая difficulty и sumary)
        $categories = $universalResult['categories'] ?? [];
        ksort($categories);

        // Получаем все возможные сложности
        $complexities = $universalResult['difficulty'] ?? [];
        ksort($complexities);
        return view('App.AppHome', [
            'Tasks' => $Tasks,
            'categories' => $categories,
            'complexities' => $complexities,
            'SolvedTasks' => $SolvedTasks
        ]);
    }
    public function TasksIDView(int $id)
    {
        $settings = Settings::find(1);
        if($settings->Home === 'no'){
            abort(403);
        }
        $task = Tasks::find($id);
        if($task){
            $data = compact('id', 'task');
            return view('App.AppTasksID', compact('data'));
        }
        else {
            return redirect()->back()->with('error', 'Задача не найдена');
        }
    }
    public function RulesView(SettingsService $settings)
    {
        if(!$settings->get('sidebar.Rules')){
            abort(403);
        }
        $sett = $settings->get('AppRulesTB') ?? '(•ิ_•ิ)?';
        if (Auth::check()) {
            return view('App.AppRules', compact('sett'));
        }
        return view('Guest.rules', compact('sett'));
    }
    public function AuthView()
    {
        if (Auth::check()) {
            return redirect('/Home');
        }
        return view('App.AppAuth');
    }
    public function SlashView()
    {
        if (Auth::check()) {
            return redirect('/Home');
        }
        return redirect('/Auth');
    }

    //----------------------------------------------------------------Other
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
}

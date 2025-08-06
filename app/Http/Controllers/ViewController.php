<?php

namespace App\Http\Controllers;

use App\Models\CheckTasks;
use App\Models\CompletedTaskTeams;
use App\Models\Tasks;
use App\Models\Teams;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
class TakeModels
{
    static function getAllTasks()
    {
        $tasks = Cache::get('All-Tasks');

        if (!isset($tasks)) {
            $tasks = Tasks::all();
//            $tasks = Tasks::with(['categoryRelashion:id,name', 'complexityRelashion:id,name'])->get()->map(function($task) {
//                return [
//                    'id' => $task->id,
//                    'name' => $task->name,
//                    'description' => $task->description,
//                    'FILES' => $task->FILES,
//                    'web_port' => $task->web_port,
//                    'db_port' => $task->db_port,
//                    'web_directory' => $task->web_directory,
//                    'solved' => $task->solved,
//                    'price' => $task->price,
//                    'oldprice' => $task->oldprice,
//                    'decide' => $task->decide,
//                    'category' => $task->categoryRelashion->name ?? null, // Обработка NULL
//                    'complexity' => $task->complexityRelashion->name ?? null, // Обработка NULL
//                    'created_at' => $task->created_at,
//                    'updated_at' => $task->updated_at
//                ];
//            })->toArray();
            Cache::tags('ModelList')->put('All-Tasks', $tasks, now()->addMinutes(10));
        }
        return $tasks;
    }
    static function getAllTeams()
    {
        $Teams = Cache::get('All-Teams');

        if (!isset($Teams)) {
            $Teams = Teams::all();
            Cache::tags('ModelList')->put('All-Teams', $Teams, now()->addMinutes(10));
        }
        return $Teams;
    }
    static function getAllDesidedTasksAndTeams()
    {
        $Desided = Cache::get('All-Desided');

        if (!isset($Desided)) {
            $Desided = CompletedTaskTeams::all();
            Cache::tags('ModelList')->put('All-Desided', $Desided, now()->addMinutes(10));
        }
        return $Desided;
    }
    static function getAllTasksNoHidden()
    {
        $Tasks = Cache::get('All-NoHidden-Tasks');

        if (!isset($Tasks)) {
            $Tasks = Tasks::all()->makeVisible('flag');
            Cache::tags('ModelList')->put('All-NoHidden-Tasks', $Tasks, now()->addMinutes(10));
        }
        return $Tasks;
    }
    static function getAllTeamsNoHidden()
    {
        $Teams = Cache::get('All-NoHidden-Teams');

        if (!isset($Teams)) {
            $Teams = Teams::all()->makeVisible(['password', 'token', 'remember_token']);
            Cache::tags('ModelList')->put('All-NoHidden-Teams', $Teams, now()->addMinutes(10));
        }
        return $Teams;
    }
}

class ViewController extends Controller
{
    public function __construct(private SettingsService $settings) {}
    // ----------------------------------------------------------------VIEW-ADMIN
    public function adminScoreboardView()
    {
        $Users = TakeModels::getAllTeams();
        $DesidedT = TakeModels::getAllDesidedTasksAndTeams();
        return view('Admin.AdminScoreboard', compact('Users', 'DesidedT'));
    }
    public function adminTeamsView()
    {
        $Teams = TakeModels::getAllTeamsNoHidden();
        return view('Admin.AdminTeams', compact('Teams'));
    }
    public function adminTasksView()
    {
        $Tasks = TakeModels::getAllTasksNoHidden();
        $universalResult = $this->processTasksUniversal($Tasks);

        // Получаем все категории (исключая difficulty и sumary)
        $categories = $universalResult['categories'] ?? [];
        ksort($categories);

        // Получаем все возможные сложности
        $complexities = $universalResult['difficulty'] ?? [];
        ksort($complexities);

        $AllComplexities = $this->settings->get('complexity');
        $AllCategories = $this->settings->get('categories');



        return view('Admin.AdminTasks', [
            'Tasks' => $Tasks,
            'categories' => $categories,
            'complexities' => $complexities,
            'infoTasks' => $this->formatToLegacyUniversal($universalResult),
            'AllComplexities' => $AllComplexities,
            'AllCategories' => $AllCategories,
        ]);
    }
    public function adminSettingsView()
    {
        $Rules = $this->settings->get('AppRulesTB') ?? '(•ิ_•ิ)?';
        $SettSidebar = $this->settings->get('sidebar');
        $TypeAuth = $this->settings->get('auth');
        return view('Admin.AdminSettings', compact('Rules', 'SettSidebar', 'TypeAuth'));
    }
    public function adminHomeView()
    {
        $Teams = TakeModels::getAllTeams();
        $Tasks = TakeModels::getAllTasks();
        $universalResult = $this->processTasksUniversal($Tasks);
        $InfoTasks = $this->formatToLegacyUniversal($universalResult);
        $CheckTasks = CheckTasks::all();
        $data = [$Tasks, $Teams, $InfoTasks, $CheckTasks];
        return view('Admin.AdminHome', compact('data'));
    }
    public function adminAuthView()
    {
        if (Auth::guard('admin')->check()) {
            // Пользователь вошел в систему...
            return redirect('/Admin');
        }
        return view('Admin.AdminAuth');
    }

    //----------------------------------------------------------------VIEW-APP
    public function statisticIDview(int $id)
    {
        $user = Teams::with([
            'solvedTasks.tasks',
            'checkTasks'
        ])->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'Пользователь не найден');
        }

        $TeamSolvedTasks = $user->solvedTasks->pluck('tasks')->filter();

        $M = TakeModels::getAllTeams()->map(function($user) {
            $user->teamlogo = asset('storage/teamlogo/' . $user->teamlogo);
            return $user;
        });

        return view('App.AppStatisticID', [
            'id' => $id,
            'M' => $M,
            'chkT' => $user->checkTasks,
            'TeamSolvedTAsks' => $TeamSolvedTasks
        ]);
    }
    public function statisticView()
    {
        $M = TakeModels::getAllTeams();
        return view('App.AppStatistic', compact('M'));
    }
    public function scoreboardView()
    {
        $M = TakeModels::getAllTeams();
        return view('App.AppScoreboard', compact('M'));
    }
    public function projectorView()
    {
        $M = TakeModels::getAllTeams();
        return view('Guest.projectorTB', compact('M'));
    }
    public function homeView()
    {
        $Tasks = TakeModels::getAllTasks();

        $SolvedTasks = Teams::find(auth()->id())->solvedTasks;
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
            'SolvedTasks' => $SolvedTasks,
        ]);
    }
    public function rulesView()
    {
        $sett = $this->settings->get('AppRulesTB') ?? '(•ิ_•ิ)?';
        if (Auth::check()) {
            return view('App.AppRules', compact('sett'));
        }
        return view('Guest.rules', compact('sett'));
    }
    public function authView()
    {
        if (Auth::check()) {
            return redirect('/Home');
        }
        return view('App.AppAuth');
    }
    public function slashView()
    {
        if (Auth::check()) {
            return redirect('/Home');
        }
        return redirect('/Auth');
    }
    public function tasksIDView(int $id)
    {
        $task = Tasks::find($id);
        if($task){
            $data = compact('id', 'task');
            return view('App.AppTasksID', compact('data'));
        }
        else {
            return redirect()->back()->with('error', 'Задача не найдена');
        }
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
}

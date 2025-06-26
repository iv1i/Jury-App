<?php

namespace App\Http\Controllers;

use App\Models\CheckTasks;
use App\Models\desided_tasks_teams;
use App\Models\Tasks;
use App\Models\User;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViewController extends Controller
{
    // ----------------------------------------------------------------VIEW-ADMIN
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

    //----------------------------------------------------------------VIEW-APP
    public function StatisticIDview(int $id, SettingsService $settings)
    {
        $user = User::with('solvedTasks.tasks')->find($id);
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
            $M = User::all();
            for ($i = 0; $i < count($M); $i++) {
                $M[$i]->teamlogo = asset('storage/teamlogo/' . $M[$i]->teamlogo);
            }
            $chkT = User::find($id)->checkTasks;
            return view('App.AppStatisticID', compact('id', 'M', 'chkT', 'TeamSolvedTAsks'));
        }
    }
    public function StatisticView(SettingsService $settings)
    {
        if(!$settings->get('sidebar.Statistics')){
            abort(403);
        }
        $M = User::all();
        return view('App.AppStatistic', compact('M'));
    }
    public function ScoreboardView(SettingsService $settings)
    {
        if(!$settings->get('sidebar.Scoreboard')){
            abort(403);
        }
        $M = User::all();
        return view('App.AppScoreboard', compact('M'));
    }
    public function ProjectorView(SettingsService $settings)
    {
        if(!$settings->get('sidebar.Projector')){
            abort(403);
        }
        $M = User::all();
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
            'SolvedTasks' => $SolvedTasks,
        ]);
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
}

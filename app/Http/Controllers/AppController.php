<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use App\Models\Tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AppController extends Controller
{
    //----------------------------------------------------------------Auth
    public function AuthTeam(Request $request)
    {
        //dd($request);
        $credentials = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $url = url("/Home");
            return redirect()->intended($url);
        }

        return redirect()->back()->withErrors([__('Incorrect username or password.')]);
    }
    public function logout(Request $request)
    {
        $settings = Settings::find(1);
        if($settings->Logout === 'no'){
            abort(403);
        }
        // Выход текущего пользователя из системы
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/Auth');
    }

    //----------------------------------------------------------------View
    public function StatisticIDview(int $id)
    {
        $user = \App\Models\User::with('solvedTasks.tasks')->find($id);
        $settings = Settings::find(1);
        if($settings->Statistics === 'no' && $id !== \auth()->id()){
            abort(403);
            //return response('Access is restricted.', 403);
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
    public function StatisticView()
    {
        $settings = Settings::find(1);
        if($settings->Statistics === 'no'){
            abort(403);
        }
        $M = \App\Models\User::all();
        return view('App.AppStatistic', compact('M'));
    }
    public function ScoreboardView()
    {
        $M = \App\Models\User::all();
        return view('App.AppScoreboard', compact('M'));
    }
    public function ProjectorView()
    {
        $settings = Settings::find(1);
        if($settings->Projector === 'no'){
            abort(403);
        }
        $M = \App\Models\User::all();
        return view('Guest.projectorTB', compact('M'));
    }
    public function HomeView()
    {
        $settings = Settings::find(1);
        if($settings->Home === 'no'){
            abort(403);
        }
        return view('App.AppHome');
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
    public function RulesView()
    {
        $settings = Settings::find(1);
        if($settings->Rules === 'no'){
            abort(403);
        }
        $sett = $settings->Rule;
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

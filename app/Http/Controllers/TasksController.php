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
use App\Models\infoTasks;
use App\Models\SolvedTasks;
use App\Models\Tasks;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TasksController extends Controller
{
    //----------------------------------------------------------------Main
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
            $this->NotifEventsOups($userAgent);
            return response()->json(['warning' => 'Вы уже решили эту задачу'], 200);
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

            $this->NotifEventsSucces($userAgent);
            $this->AppEvents();
            $this->AdminEvents();

        }

        $this->NotifEventsError($userAgent);

        return response()->json(['success' => false,'message' => 'Флаг неверный!'], 200);
        //return redirect()->route('TasksID', ['id' => $taskid])->with('error', 'Флаг неверный!');
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
        $InfoTasks = infoTasks::all();
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
}

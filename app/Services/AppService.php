<?php

namespace App\Services;

use App\Http\Requests\CheckFlagRequest;
use App\Models\SolvedTasks;
use App\Models\Tasks;
use App\Models\Teams;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AppService
{

    public function __construct(private Utility $utility, private EventsService $eventsService)
    {
    }

    //----------------------------------------------------------------APP
    public function checkFlag(CheckFlagRequest $request): array
    {
        $taskId = $request->input('ID');
        $task = Tasks::findOrFail($taskId);
        $authUserId = Auth::id();

        if ($this->isTaskAlreadySolved($authUserId, $taskId)) {
            return [
                'type' => 'warning',
                'success' => true,
                'message' => 'Вы уже решили эту задачу',
                'status' => 200
            ];
        }

        if ($request->input('flag') !== $task->flag) {
            return [
                'success' => false,
                'message' => 'Флаг неверный!',
                'status' => 200
            ];
        }

        $this->handleCorrectFlag($task, $authUserId, $request->input('complexity'));

        return [
            'success' => true,
            'message' => __('The flag is correct!'),
            'status' => 200
        ];
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

        $this->eventsService->appEvents();
        $this->eventsService->adminEventsUsers();

        $this->utility->cacheClear();
    }

    private function createSolvedTask(Tasks $task, int $userId): void
    {
        $solvedTask = new SolvedTasks();
        $solvedTask->id = $this->utility->makeId(SolvedTasks::all());
        $solvedTask->teams_id = $userId;
        $solvedTask->tasks_id = $task->id;
        $solvedTask->price = $task->price;
        $solvedTask->style_tasks = $this->getComplexityStyle($task->complexity);
        $solvedTask->save();
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
        $checkTasks->{$complexity} += 1;

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

    public function downloadFile($md5file, $id, Request $request): array
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
                    return [
                        'success' => false,
                        'status' => 404
                    ];
                }
                $extension = pathinfo($f, PATHINFO_EXTENSION);
                $name = 'file_' . $task->name  . '_'. $k+1 . '.' . $extension;
                // Загружаем файл
                return [
                    'success' => true,
                    'filename' => $name,
                    'filepath' => $filePath,
                    'status' => 200
                ];
            }
        }
        return [
            'success' => false,
            'status' => 404
        ];
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskParticipant;

class CabinetController extends Controller
{
    public function index()
    {
        $data = [
            'tasks' => $this->ForTodayList(),
            'projects' => $this->projectsList(),
        ];
//        dump($data);
        return view('cabinet.main', $data);
    }

    /*
     * Список "Задачи на сегодня", в котором выводятся все задачи со статусом "В работе",
     * в которых текущий пользователь имеет статус "Исполнитель".
     */
    public function ForTodayList()
    {
        $user_id = 3;
        $tasks_id = TaskParticipant::where('user_id', $user_id)
            ->pluck('task_id')
            ->toArray();

        return Task::forToday($tasks_id, 'in_progress', 'desc');
    }

    /*
     * Список проектов, в которых участвует текущий пользователь.
     */
    public function projectsList()
    {
        return ProjectsController::index();
    }
}

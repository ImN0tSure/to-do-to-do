<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskParticipant;

class CabinetController extends Controller
{
    public function index()
    {
        $data = [
            'for_today' => $this->ForTodayList(),
            'projects' => $this->projectsList(),
        ];

        return view('cabinet.index', $data);
    }

    /*
     * Список "Задачи на сегодня", в котором выводятся все задачи со статусом "В работе",
     * в которых текущий пользователь имеет статус "Исполнитель".
     */
    public function ForTodayList()
    {
        $user_id = 3;
        $tasks_id = TaskParticipant::where('user_id', $user_id)->pluck('task_id')->toArray();

        return Task::forToday($tasks_id);
    }

    /*
     * Список проектов, в которых участвует текущий пользователь.
     */
    public function projectsList()
    {
        return ProjectsController::index();
    }
}

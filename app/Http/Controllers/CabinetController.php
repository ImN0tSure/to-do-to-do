<?php

namespace App\Http\Controllers;

use App\Models\ProjectParticipants;
use App\Models\Projects;
use App\Models\TaskParticipants;
use App\Models\Tasks;
use Illuminate\Http\Request;

class CabinetController extends Controller
{
    public function index()
    {
        $response = [
            'for_today' => $this->ForTodayList(),
            'projects' => $this->projectsList(),
        ];

        return $response;
    }

    /*
     * Список "Задачи на сегодня", в котором выводятся все задачи со статусом "В работе",
     * в которых текущий пользователь имеет статус "Исполнитель".
     */
    public function ForTodayList()
    {
        $user_id = 3;
        $tasks_id = TaskParticipants::where('user_id', $user_id)->pluck('task_id')->toArray();

        return Tasks::forToday($tasks_id);
    }

    /*
     * Список проектов, в которых участвует текущий пользователь.
     */
    public function projectsList()
    {
        return ProjectsController::index();
    }
}

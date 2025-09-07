<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class CabinetController extends Controller
{
    public function index()
    {
        $data = [
            'tasks' => $this->ForTodayList(),
            'projects' => $this->projectsList(),
        ];
        return view('cabinet.main', $data);
    }

    /*
     * Список "Задачи на сегодня", в котором выводятся все задачи со статусом "В работе",
     * в которых текущий пользователь имеет статус "Исполнитель".
     */
    public function ForTodayList()
    {
        $user_id = Auth::id();
        return Task::where('executor_id', $user_id)->with(['project:url'])->get();
    }

    /*
     * Список проектов, в которых участвует текущий пользователь.
     */
    public function projectsList()
    {
        return ProjectController::index();
    }
}

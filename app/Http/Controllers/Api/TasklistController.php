<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Tasklist;
use App\Services\GetProjectId;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTasklistRequest;
use Illuminate\Support\Facades\Gate;

class TasklistController extends Controller
{
    public function index(Request $request, $project_url) {

        $project_id = GetProjectId::byUrl($project_url);

        $tasklists = Tasklist::where('project_id', $project_id)->select('id', 'name', 'project_id')->get();

        return response()->json([
            'success' => true,
            'tasklists' => $tasklists
        ]);
    }

    public function store(StoreTasklistRequest $request, $project_url) {
        $validate_data = $request->validated();

        $project_id = GetProjectId::byUrl($project_url);

        Gate::authorize('tasklist.create', [Tasklist::class, $project_id]);

        $validate_data['project_id'] = $project_id;

        $tasklist = Tasklist::create($validate_data);

        return response()->json([
            'success' => true,
            'tasklist' => [
                'id' => $tasklist->id,
                'name' => $tasklist->name,
                'project_id' => $tasklist->project_id,
            ]
        ]);
    }

    public function update(StoreTasklistRequest $request, $project_url, $tasklist_id) {
        $project_id = GetProjectId::byUrl($project_url);
        Gate::authorize('tasklist.update', $project_id);

        $validate_data = $request->validated();
        unset($validate_data['oldName']);

        $tasklist = Tasklist::findOrFail($tasklist_id);
        $tasklist->update($validate_data);

        return response()->json([
            'success' => true,
            'message' => 'Список задач переименован.'
        ]);
    }

    public function destroy($project_url, $tasklist_id) {
        $project_id = GetProjectId::byUrl($project_url);
        Gate::authorize('tasklist.delete', [Tasklist::class, $project_id]);

        Tasklist::destroy($tasklist_id);
        Task::where('tasklist_id', $tasklist_id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Список задач вместе с задачами успешно удалён.'
        ]);
    }
}

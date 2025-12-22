<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tasklist;
use App\Services\GetProjectId;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTasklistRequest;

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

        $this->authorize('create', [Tasklist::class, $project_id]);

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
}

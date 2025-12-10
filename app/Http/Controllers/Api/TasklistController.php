<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tasklist;
use App\Services\GetProjectId;
use Illuminate\Http\Request;

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
}

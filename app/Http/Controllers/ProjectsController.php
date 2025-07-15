<?php

namespace App\Http\Controllers;

use App\Models\ProjectParticipant;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public static function index()
    {
        $user_id = 3;

        $projects_id = ProjectParticipant::where('user_id', $user_id)
            ->pluck('project_id')
            ->toArray();

        return Project::getProjectsList($projects_id);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('project.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validData = $request->validate([
            'name' => 'required',
            'description' => 'required | max:255',
            #'end_date' => 'required | timestamp | after:yesterday',
            'end_date' => 'max:255',
        ]);


        do {
            $validData['url'] = Str::random(10);
        } while (Project::where('url', $validData['url'])->first() != null);

        $validData['begin_date'] = date('Y-m-d H:i:s');

        $participate = [
            'user_id' => 3,
            'project_id' => Project::createProject($validData),
            'status' => 1,
        ];

        ProjectParticipant::create($participate);
        return redirect()->route('project.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

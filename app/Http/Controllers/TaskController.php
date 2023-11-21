<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, int $projectId)
    {
        $project = Project::find($projectId);
        return view('pages.' . 'createTask')->with(['projectId'=>$projectId, 'users'=>$project->users]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $project_)
    {
        // Validate input


        $validated = $request->validate([
            'title' => 'required|string|min:5|max:100|',
            'description' => 'required|string|min:10|max:1024',
            'deadline' => 'nullable|date|after_or_equal:today',
            'users' => 'nullable'
        ]);
        // Add Policy thing


        $task = new Task();
        $task->title = $validated['title'];
        $task->description = $validated['description'];
        $task->opened_user_id= Auth::user()->id;
        $task->deadline = $validated['deadline'];
        $task->save();

        DB::insert('insert into project_task (task_id, project_id) values (?, ?)', [$task->id, $project_]);
        if($validated['users']) DB::insert('insert into task_user (user_id, task_id) values (?, ?)', [$validated['users'], $task->id]);
        $res = DB::table('task_user')->get();
        return view('pages.' . 'task')->with(['task'=>Task::find($task->id),'res'=>$res]);

    }

    /**
     * Display the specified resource.
     */
    public function show(int $project,int $task)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
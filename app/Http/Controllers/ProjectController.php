<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
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
    public function create()
    {
        $this->authorize('create', Project::class);
        
        return view('pages.newProjectForm');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:100|',
            'description' => 'required|string|min:10|max:1024',
            'deadline' => 'nullable|date|after_or_equal:' . date('d-m-Y'),
        ]);

        // Add Policy thing
        $this->authorize('create', Project::class);

        $project = new Project();
        $project->title = $validated['title'];
        $project->description = $validated['description'];
        $project->deadline = isset($validated['deadline']) ? $validated['deadline'] : null;
        $project->user_id = Auth::user()->id;
        $project->save();

        $project->users()->attach(Auth::user()->id);

        return redirect()->route('home');
        // TODO: use this when project page is done
        // return redirect()->route('show_project', ['projectId' => $project->id]);
    }
    /**
     * Display the specified resource.
     */
    public function show(int $projectId)
    {
        $project=Project::find($projectId);

        if ($project == null)
            return abort(404);

        $this->authorize('view',[Project::class,$project]);
        $users = $project->users;

        $completed_task = DB::table('project_task')
            ->join('tasks','project_task.task_id','=','tasks.id')
            ->where('project_id','=',$projectId)
            ->where('tasks.status','=','closed')->count();
        $open_task = DB::table('project_task')
            ->join('tasks','project_task.task_id','=','tasks.id')
            ->where('project_id','=',$projectId)
            ->where('tasks.status','=','open')->count();
        $all_task = $completed_task + $open_task;
        return view('pages.project',['project'=>$project, 'team'=>$users->slice(0,4),'allTasks'=>$all_task, 'completedTasks'=>$completed_task]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $projectId)
    {
        $project = Project::find($projectId);

        if ($project == null)
            return abort(404);

        $this->authorize('delete', [Project::class, $project]);

        $project->delete();

        $projects = Project::all();

        return redirect()->route('home', ['projects' => $projects]);
        // TODO: redirect to "My projects page"
        // return redirect()->route('my_projects');
    }

    public function showTasks(int $projectId)
    {
        $project=Project::find($projectId);

        if ($project == null)
            return abort(404);

        $this->authorize('view',[Project::class,$project]);
        $tasks = DB::table('project_task')
            ->join('tasks','project_task.task_id','=','tasks.id')
            ->where('project_id','=',$projectId)->get();
            
        return view('pages.tasks',['project'=>$project, 'tasks'=>$tasks]);
    }
}

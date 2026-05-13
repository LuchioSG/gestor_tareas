<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
//use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with(['category', 'tags', 'assignees'])
            ->where('creator_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        $tags = \App\Models\Tag::all();
        $users = \App\Models\User::where('id', '!=', auth()->id())->get();

        return view('tasks.create', compact('categories', 'tags', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create([
            ...$request->validated(),
            'creator_id' => auth()->id(),
        ]);

        if ($request->has('tags')) {
            $task->tags()->sync($request->tags);
        }

        if ($request->has('assignees')) {
            $task->assignees()->sync(
                collect($request->assignees)->mapWithKeys(fn($id) => [$id => ['role' => 'assignee']])
            );
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Tarea creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //$this->authorize('view', $task);
        
        $task->load(['category', 'tags', 'assignees', 'attachments']);

        return view('tasks.show', compact('task'));
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Mail\TaskCreated;
use Illuminate\Support\Facades\Mail;
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

        Mail::to(auth()->user()->email)->send(new TaskCreated($task));

        return redirect()->route('tasks.index')
            ->with('success', 'Tarea creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        
        $task->load(['category', 'tags', 'assignees', 'attachments']);

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $categories = \App\Models\Category::all();
        $tags = \App\Models\Tag::all();
        $users = \App\Models\User::where('id', '!=', auth()->id())->get();

        return view('tasks.edit', compact('task', 'categories', 'tags', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $task->update($request->validated());

        $task->tags()->sync($request->tags ?? []);

        $task->assignees()->sync(
            collect($request->assignees ?? [])->mapWithKeys(fn($id) => [$id => ['role' => 'assignee']])
        );

        return redirect()->route('tasks.index')
            ->with('success', 'Tarea actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Tarea eliminada correctamente.');
    }
}

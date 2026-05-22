<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $task->comments()->create([
            'body'    => $request->body,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Comentario agregado.');
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Comentario eliminado.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Events\TaskCommentCreated;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'message' => 'required|string|max:255',
        ]);

        $comment = $request->user()->comments()->create($validated);

        broadcast(new TaskCommentCreated($comment))->toOthers();

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('update', $comment);
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $comment->update($validated);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return back();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DeletedTasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $tasks = Task::with('reporter:id,name', 'assignee:id,name')
            ->filter(request(['search', 'reporter_id', 'assignee_id', 'status']))
            ->onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $searchFilters = $request->only([
            'search',
            'reporter_id',
            'assignee_id',
            'status',
        ]);

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
            'can' => [
                'create' => false,
            ],
            'filters' => $searchFilters,
            'deleted' => true
        ]);
    }

    /**
     * Restore the specified task
     */
    public function restore($task_id): RedirectResponse
    {
        $task = Task::withTrashed()->findOrFail($task_id);

        if (empty($task->deleted_at)) {
            abort(404);
        }

        Gate::authorize('restore', $task);

        $task->restore();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function forceDelete($task_id): RedirectResponse
    {
        $task = Task::withTrashed()->findOrFail($task_id);

        if (empty($task->deleted_at)) {
            abort(404);
        }

        Gate::authorize('forceDelete', $task);

        $task->forceDelete();

        return redirect()->back();
    }
}

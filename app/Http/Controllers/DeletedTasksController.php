<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
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
        $tasks = Task::with([
                'reporter' => function ($query) {
                    $query->withTrashed()->select('id', 'name', 'deleted_at');
                },
                'assignee' => function ($query) {
                    $query->withTrashed()->select('id', 'name', 'deleted_at');
                },
            ])
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

        if (!empty($searchFilters['reporter_id'])) {
            $reporterName = $tasks->first(function ($task) use ($searchFilters) {
                return $task->reporter_id === $searchFilters['reporter_id'];
            })?->reporter->name
            ?? User::where('id', $searchFilters['reporter_id'])->withTrashed()->first()?->name;
        }

        if (!empty($searchFilters['assignee_id'])) {
            $assigneeName = $tasks->first(function ($task) use ($searchFilters) {
                return $task->assignee_id === $searchFilters['assignee_id'];
            })?->assignee->name
            ?? User::where('id', $searchFilters['assignee_id'])->withTrashed()->first()?->name;
        }

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
            'can' => [
                'create' => false,
            ],
            'filters' => $searchFilters,
            'reporterName' => $reporterName ?? '',
            'assigneeName' => $assigneeName ?? '',
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

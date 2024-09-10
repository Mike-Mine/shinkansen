<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $tasks = Task::with('reporter:id,name', 'assignee:id,name')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
            'can' => [
                'create' => Gate::allows('create', Task::class),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Tasks/Create', [
            'assignees' => User::permission('fulfill tasks')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Task::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'assignee_id' => 'nullable|exists:users,id',
        ]);

        $validated['reporter_id'] = $request->user()->id;
        $task = Task::create($validated);

        return redirect(route('tasks.show', $task));
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): Response
    {
        return Inertia::render('Tasks/Show', [
            'task' => $task->load('reporter:id,name', 'assignee:id,name'),
            'comments' => $task->comments()->with('user:id,name')->orderBy('created_at', 'desc')->get(),
            'statuses' => TaskStatus::cases(),
            'assignees' => User::permission('fulfill tasks')->get(),
            'can' => [
                'delete' => Gate::allows('delete', $task),
                'updateStatus' => Gate::allows('updateStatus', $task),
                'updateAssignee' => Gate::allows('updateAssignee', $task),
                'update' => Gate::allows('update', $task),
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        if (
            ((!empty($request->input('title')) || !empty($request->input('description'))) && Gate::denies('update', $task))
            || (!empty($request->input('status')) && Gate::denies('updateStatus', $task))
            || (!empty($request->input('assignee_id')) && Gate::denies('updateAssignee', $task))
        ) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'sometimes|required',
            'assignee_id' => [
                'sometimes',
                'nullable',
                'exists:users,id,deleted_at,NULL',
            ],
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:255',
        ]);

        $task->update($validated);

        return back()->with('success', 'Task updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        Gate::authorize('delete', $task);

        $task->delete();

        return redirect(route('tasks.index'));
    }
}

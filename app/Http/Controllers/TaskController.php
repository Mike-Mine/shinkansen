<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Tasks/Index', [
            'tasks' => Task::with('reporter:id,name', 'assignee:id,name')->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Tasks/Create', [
            'users' => User::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
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
            'availableStatuses' => collect(TaskStatus::cases())
                ->filter(function ($status) use($task) {
                    return $status->value !== $task->status->value;
                }),
            'users' => User::select('id', 'name')->get()
        ]);
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
    public function destroy(Task $task): RedirectResponse
    {
        Gate::authorize('delete', $task);

        $task->delete();

        return redirect(route('tasks.index'));
    }

    /**
     * Updates the status of a task.
     *
     * @param Request $request The HTTP request containing the new status.
     * @param Task $task The task to update.
     * @return RedirectResponse A redirect response to the task's show page.
     */
    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required',
        ]);

        $task->update($validated);

        return redirect(route('tasks.show', $task));
    }

    public function updateAssignee(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'assignee_id' => [
                'nullable',
                Rule::exists('users', 'id')->whereNull('deleted_at')->where(function ($query) {
                    $query->whereNotNull('id');
                }),
            ],
        ]);

        $task->update($validated);

        return back()->with('success', 'Assignee updated successfully');
    }
}

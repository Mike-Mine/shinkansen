<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Events\TaskUpdated;
use App\Models\Task;
use App\Models\User;
use App\Rules\TaskAttributePermission;
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
            ->orderBy('updated_at', 'desc')
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
                'create' => Gate::allows('create', Task::class),
            ],
            'filters' => $searchFilters,
            'reporterName' => $reporterName ?? '',
            'assigneeName' => $assigneeName ?? '',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $assignees = collect([0 => User::DEFAULT_UNASSIGNED_NAME])->merge(User::permission('fulfill tasks')->get()->pluck('name', 'id'));

        return Inertia::render('Tasks/Create', [
            'assignees' => $assignees,
            'defaultAssigneeName' => User::DEFAULT_UNASSIGNED_NAME,
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
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
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
        $assignees = User::permission('fulfill tasks')->get()
            ->map(fn ($user) => ['id' => $user->id, 'name' => $user->name])
            ->prepend(['id' => 0, 'name' => User::DEFAULT_UNASSIGNED_NAME]);

        $reporters = User::permission('create tasks')->get()
            ->map(fn ($user) => ['id' => $user->id, 'name' => $user->name]);

        $task = $task->load([
            'reporter' => function ($query) {
                $query->withTrashed()->select('id', 'name', 'deleted_at');
            },
            'assignee' => function ($query) {
                $query->withTrashed()->select('id', 'name', 'deleted_at');
            },
        ]);

        if ($task->assignee?->deleted_at !== null) {
            $assignees->push([
                'id' => $task->assignee_id,
                'name' => $task->assignee->name . ' (Unlicensed)',
            ]);
        }

        if ($task->reporter?->deleted_at !== null) {
            $reporters->push([
                'id' => $task->reporter_id,
                'name' => $task->reporter->name . ' (Unlicensed)',
            ]);
        }

        return Inertia::render('Tasks/Show', [
            'task' => $task,
            'comments' => $task->comments()
                ->with(['user' => function ($query) {
                    $query->withTrashed()->select('id', 'name', 'deleted_at');
                }])
                ->orderBy('created_at', 'desc'
                )->paginate(10),
            'statuses' => TaskStatus::cases(),
            'assignees' => $assignees,
            'reporters' => $reporters,
            'defaultAssigneeName' => User::DEFAULT_UNASSIGNED_NAME,
            'can' => [
                'delete' => Gate::allows('delete', $task),
                'updateStatus' => Gate::allows('updateStatus', $task),
                'updateAssignee' => Gate::allows('updateAssignee', $task),
                'update' => Gate::allows('update', $task),
                'manageComments' => auth()->user()->can('manage comments'),
                'manageDates' => Gate::allows('manageDates', $task),
                'changeReporter' => auth()->user()->can('change reporter'),
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        $taskAttributePermissionRule = new TaskAttributePermission($task);

        $validationRules = [
            'status' => [
                'sometimes',
                'required',
            ],
            'assignee_id' => [
                'sometimes',
                'nullable',
                'exists:users,id',
            ],
            'reporter_id' => [
                'sometimes',
                'nullable',
                'exists:users,id',
            ],
            'title' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],
            'start_date' => [
                'sometimes',
                'nullable',
                'date',
            ],
            'due_date' => [
                'sometimes',
                'nullable',
                'date',
            ],
        ];

        foreach ($validationRules as $key => $rule) {
            $validationRules[$key][] = $taskAttributePermissionRule;
        }

        $validated = $request->validate($validationRules);

        $task->update($validated);

        broadcast(new TaskUpdated($task))->toOthers();

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

<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $topReporter = User::withCount(['reportedTasks' => function ($query) {
            $query->where('status', TaskStatus::DONE);
        }])->orderBy('reported_tasks_count', 'desc')->first();

        $topAssignee = User::withCount(['assignedTasks' => function ($query) {
            $query->where('status', TaskStatus::DONE);
        }])->orderBy('assigned_tasks_count', 'desc')->first();

        return Inertia::render('Dashboard', [
            'assignedTasks' => auth()->user()->assignedTasks()->limit(5)->get(),
            'reportedTasks' => auth()->user()->reportedTasks,
            'topReporter' => $topReporter,
            'topAssignee' => $topAssignee,
            'taskCounts' => Task::getTaskCounts(),
            'totalUsers' => User::count(),
        ]);
    }
}

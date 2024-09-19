<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [
            'assignedTasks' => auth()->user()->assignedTasks()->limit(5)->get(),
            'reportedTasks' => auth()->user()->reportedTasks,
        ]);
    }
}

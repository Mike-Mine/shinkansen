<?php

use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeletedTasksController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('password.confirm');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/chat', [ChatMessageController::class, 'index'])->name('chat.index')->middleware('can:view chat messages');
    Route::post('/chat', [ChatMessageController::class, 'store'])->name('chat.store');
    Route::put('/chat/{chatMessage}', [ChatMessageController::class, 'update'])->name('chat.update');
    Route::delete('/chat/{chatMessage}', [ChatMessageController::class, 'destroy'])->name('chat.destroy');

    Route::resource('tasks', TaskController::class)->middleware('can:view tasks');

    Route::get('/deleted-tasks', [DeletedTasksController::class, 'index'])->name('tasks.deleted')->middleware('can:view deleted tasks');

    Route::put('/deleted-tasks/{task}', [DeletedTasksController::class, 'restore'])->name('tasks.restore');

    Route::delete('/deleted-tasks/{task}', [DeletedTasksController::class, 'forceDelete'])->name('tasks.forceDelete');

    Route::resource('comments', CommentController::class)
        ->only('store', 'update', 'destroy');

    Route::resource('users', UserController::class)->middleware('can:view users');
});

require __DIR__.'/auth.php';

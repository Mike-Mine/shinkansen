<?php

use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/chat', [ChatMessageController::class, 'index'])->name('chat.index');
    Route::post('/chat', [ChatMessageController::class, 'store'])->name('chat.store');
    Route::put('/chat/{chatMessage}', [ChatMessageController::class, 'update'])->name('chat.update');
    Route::delete('/chat/{chatMessage}', [ChatMessageController::class, 'destroy'])->name('chat.destroy');
});

Route::resource('tasks', TaskController::class)
    ->middleware(['auth', 'verified']);

Route::resource('comments', CommentController::class)
    ->only('store', 'update', 'destroy')
    ->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';

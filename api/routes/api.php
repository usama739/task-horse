<?php

use App\Http\Controllers\ClerkWebhookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/webhooks', [ClerkWebhookController::class, 'handle']);

// ------------------  Protected routes via clerk Token from frontend (just like Sanctum) and role-based authorization ------------------ //
Route::middleware(['verify.clerk', 'role:admin,member'])->group(function () {
    Route::get('/me', function () {
        return response()->json(auth()->user());
    });

    Route::get('/users/{clerk_id}', [UserController::class, 'findByClerkId']);

    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('tasks.show');

    Route::post('/tasks/{id}/comments', [TaskCommentController::class, 'store'])->name('comments.store');
});

Route::middleware(['verify.clerk', 'role:admin'])->group(function () {
    Route::post('/organizations', [OrganizationController::class, 'store']);

    Route::get('/dashboard/task-status-trend', [DashboardController::class, 'taskStatusTrend']);
    Route::get('/dashboard/completed-tasks-by-user', [DashboardController::class, 'completedTasksByUser']);
    Route::get('/dashboard/tasks-by-priority', [DashboardController::class, 'tasksByPriority']);
    Route::get('/dashboard/tasks-by-project', [DashboardController::class, 'tasksByProject']);
    Route::get('/dashboard/task-priority-counts', [DashboardController::class, 'taskPriorityCounts']);
    Route::get('/dashboard/overview-counts', [DashboardController::class, 'overviewCounts']);

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::put('/projects/{id}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    Route::delete('/comments/{id}', [TaskCommentController::class, 'destroy'])->name('comments.destroy');
});

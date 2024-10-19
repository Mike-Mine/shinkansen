<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create tasks');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('update tasks')
            || $task->reporter()->is($user);
    }

    /**
     * Determine whether the user can update the task status.
     */
    public function updateStatus(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('update task status')
            || $task->reporter()->is($user)
            || $task->assignee()->is($user);
    }

    /**
     * Determine whether the user can update the task status.
     */
    public function updateAssignee(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('assign tasks')
            || $task->reporter()->is($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        return $task->reporter()->is($user)
            || $user->hasPermissionTo('delete tasks');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('restore tasks');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('force delete tasks');
    }

    public function manageDates(User $user, Task $task): bool
    {
        return $task->reporter()->is($user) || $user->hasPermissionTo('update task dates');
    }

    public function changeReporter(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('change reporter');
    }
}

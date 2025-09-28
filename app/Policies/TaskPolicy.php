<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use App\Services\isAllowedForUpdate;
use App\Services\isStatusHigherThan;
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
    public function create(User $user, $project_id): bool
    {
        return isStatusHigherThan::executor($project_id);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, $task_id): bool
    {
        return isAllowedForUpdate::task($task_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, $project_id): bool
    {
        return isStatusHigherThan::executor($project_id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        //
    }
}

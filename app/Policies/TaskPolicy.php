<?php

namespace App\Policies;

use App\User;
use App\Task;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;


    public function before($user, $abliity) {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }
    /**
     * Determine whether the user can view the task.
     *
     * @param  App\User  $user
     * @param  App\Task  $task
     * @return mixed
     */
    public function view(User $user, Task $task)
    {
        //
    }

    /**
     * Determine whether the user can create tasks.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the task.
     *
     * @param  App\User  $user
     * @param  App\Task  $task
     * @return mixed
     */
    public function update(User $user, Task $task)
    {
        //
    }

    /**
     * Determine whether the user can delete the task.
     *
     * @param  App\User  $user
     * @param  App\Task  $task
     * @return mixed
     */
    public function delete(User $user, Task $task)
    {
        return $user->id === $task->creator_id;
    }

    public function retry(User $user, Task $task)
    {
        return $user->id === $task->creator_id;
    }

    public function terminate(User $user, Task $task)
    {
        return $user->id === $task->creator_id;
    }
}

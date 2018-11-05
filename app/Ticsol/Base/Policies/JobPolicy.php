<?php

namespace App\Ticsol\Base\Policies;

use App\Ticsol\Components\Models\Job;
use App\Ticsol\Components\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        $roles = $user->load('roles.permissions')->roles;
        foreach ($roles as $role) {
            if ($role->permissions->contains('name', 'full-job')) {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can view the list of jobs.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Job  $job
     * @return mixed
     */
    function list(User $user) {
        return true;
    }

    /**
     * Determine whether the user can view the job.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Job  $job
     * @return mixed
     */
    public function view(User $user, Job $job)
    {
        return true;
    }

    /**
     * Determine whether the user can create jobs.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        $roles = $user->load('roles.permissions')->roles;
        foreach ($roles as $role) {
            if ($role->permissions->contains('name', 'create-job')) {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can update the job.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Job  $job
     * @return mixed
     */
    public function update(User $user, Job $job)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the job.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Job  $job
     * @return mixed
     */
    public function delete(User $user, Job $job)
    {
        $roles = $user->load('roles.permissions')->roles;
        foreach ($roles as $role) {
            if ($role->permissions->contains('name', 'delete-job')) {
                return true;
            }
        }
    }
}

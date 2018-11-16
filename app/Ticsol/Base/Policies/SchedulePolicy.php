<?php

namespace App\Ticsol\Base\Policies;

use App\Ticsol\Components\Models\User;
use App\Ticsol\Components\Models\Schedule;
use Illuminate\Auth\Access\HandlesAuthorization;

class SchedulePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        $roles = $user->load('roles.permissions')->roles;
        foreach ($roles as $role) {
            if ($role->permissions->contains('name', 'full-schedule')) {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can view the list of schedule.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Schedule  $schedule
     * @return mixed
     */
    function list(User $user) {
        return true;
    }

    /**
     * Determine whether the user can view the schedule.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Schedule  $schedule
     * @return mixed
     */
    public function view(User $user, Schedule $schedule)
    {
        //
    }

    /**
     * Determine whether the user can create schedules.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        $roles = $user->load('roles.permissions')->roles;
        foreach ($roles as $role) {
            if ($role->permissions->contains('name', 'create-schedule')) {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can update the schedule.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Schedule  $schedule
     * @return mixed
     */
    public function update(User $user, Schedule $schedule)
    {
        $roles = $user->load('roles.permissions')->roles;
        foreach ($roles as $role) {
            if ($role->permissions->contains('name', 'update-schedule')) {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can delete the schedule.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Schedule  $schedule
     * @return mixed
     */
    public function delete(User $user, Schedule $schedule)
    {
        $roles = $user->load('roles.permissions')->roles;
        foreach ($roles as $role) {
            if ($role->permissions->contains('name', 'delete-schedule')) {
                return true;
            }
        }
    }
}

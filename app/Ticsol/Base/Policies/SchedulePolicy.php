<?php

namespace App\Ticsol\Base\Policies;

use App\Ticsol\Components\Models\User;
use App\Ticsol\Components\Models\Schedule;
use Illuminate\Auth\Access\HandlesAuthorization;

class SchedulePolicy
{
    use HandlesAuthorization;

    protected $full;
    protected $list;
    protected $view;
    protected $create;
    protected $update;
    protected $delete;

    public function before($user, $ability)
    {
        $roles = $user->load('roles.permissions')->roles;
        foreach ($roles as $role) {
            $this->full = $role->permissions->contains('name', 'full-schedule');
            $this->list = $role->permissions->contains('name', 'list-schedule');
            $this->view = $role->permissions->contains('name', 'view-schedule');
            $this->create = $role->permissions->contains('name', 'create-schedule');
            $this->update = $role->permissions->contains('name', 'update-schedule');
            $this->delete = $role->permissions->contains('name', 'delete-schedule');
        }
    }

    /**
     * Determine whether the user can view the list of schedule.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @return mixed
     */
    function list(User $user) {
        return true;
    }

    /**
     * Determine whether the user can view the schedule.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Schedule  $schedule
     * @return mixed
     */
    public function view(User $user, Schedule $schedule)
    {
        return $schedule->client_id == $user->client_id;
    }

    /**
     * Determine whether the user can create schedules.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @return mixed
     */
    public function create(User $user, $userId, $eventType)
    {
        if($eventType == 'unavailable')
            return $user->id == $userId;

        return $this->full || $this->create;
    }

    /**
     * Determine whether the user can update the schedule.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Schedule  $schedule
     * @return mixed
     */
    public function update(User $user, Schedule $schedule)
    {
        if ($schedule->client_id != $user->client_id) {
            return false;
        }

        if($schedule->event_type == 'unavailable')
            return $user->id == $schedule->user_id;

        return $this->full || $this->update;
    }

    /**
     * Determine whether the user can delete the schedule.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Schedule  $schedule
     * @return mixed
     */
    public function delete(User $user, Schedule $schedule)
    {
        if ($schedule->client_id != $user->client_id) {
            return false;
        }
        
        return $this->full || $this->delete;
    }
}

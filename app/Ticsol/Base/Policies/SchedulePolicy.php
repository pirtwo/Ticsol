<?php

namespace App\Ticsol\Base\Policies;

use App\Ticsol\Components\Models\User;
use App\Ticsol\Components\Models\Schedule;
use Illuminate\Auth\Access\HandlesAuthorization;

class SchedulePolicy
{
    use HandlesAuthorization;

    protected $isowner;
    protected $full;
    protected $list;
    protected $view;
    protected $create;
    protected $update;
    protected $delete;

    public function before($user, $ability)
    {      
        $permissions = $user->permissions;   
        $this->isowner = $user->isowner;       
        $this->full = $permissions->contains('full-schedule');
        $this->list = $permissions->contains('list-schedule');
        $this->view = $permissions->contains('view-schedule');
        $this->create = $permissions->contains('create-schedule');
        $this->update = $permissions->contains('update-schedule');
        $this->delete = $permissions->contains('delete-schedule');    
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
    public function create(User $user, $assigneeId, $eventType)
    {
        // can create unavailable
        if($eventType == 'unavailable')
            return $user->id == $assigneeId;

        // can create own
        if($user->id == $assigneeId)
            return $this->isowner || $this->full || $this->create;

        // can create others
        return $this->isowner || $this->full;
    }

    /**
     * Determine whether the user can update the schedule.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Schedule  $schedule
     * @return mixed
     */
    public function update(User $user, Schedule $schedule, $assigneeId)
    {
        if ($schedule->client_id != $user->client_id) {
            return false;
        }

        $eventType = $schedule->event_type;

        // can update unavailable
        if($eventType == 'unavailable')
            return $user->id == $assigneeId;

        // can update own
        if($user->id == $assigneeId)
            return $this->isowner || $this->full || $this->update;

        // can update others
        return $this->isowner || $this->full;
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
        
        $assigneeId = $schedule->user_id;
        $eventType = $schedule->event_type;

        // can update unavailable
        if($eventType == 'unavailable')
            return $user->id == $assigneeId;

        // can update own
        if($user->id == $assigneeId)
            return $this->isowner || $this->full || $this->delete;

        // can update others
        return $this->isowner || $this->full;
    }
}

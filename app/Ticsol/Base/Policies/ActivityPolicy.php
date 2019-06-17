<?php

namespace App\Ticsol\Base\Policies;

use App\Ticsol\Components\Models\User;
use App\Ticsol\Components\Models\Activity;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        //
    }

    /**
     * Determine whether the user can view the list of jobs.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @return mixed
     */
    function list(User $user) {
        return true;
    }

    /**
     * Determine whether the user can view the activity.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Activity  $activity
     * @return mixed
     */
    public function view(User $user, Activity $activity)
    {
        return $activity->client_id == $user->client_id && $activity->creator_id == $user->id;
    }

    /**
     * Determine whether the user can create activities.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the activity.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Activity  $activity
     * @return mixed
     */
    public function update(User $user, Activity $activity)
    {
        return $activity->client_id == $user->client_id && $activity->creator_id == $user->id;
    }

    /**
     * Determine whether the user can delete the activity.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Activity  $activity
     * @return mixed
     */
    public function delete(User $user, Activity $activity)
    {
        return $activity->client_id == $user->client_id && $activity->creator_id == $user->id;
    }
}

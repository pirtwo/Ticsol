<?php

namespace App\Ticsol\Base\Policies;

use App\Ticsol\Components\Models\Activity;
use App\Ticsol\Components\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    protected $isowner;
    protected $full;
    protected $list;
    protected $listAll;
    protected $view;
    protected $viewAll;
    protected $create;
    protected $update;
    protected $delete;

    public function before($user, $ability)
    {
        $permissions = $user->permissions;
        $this->isowner = $user->isowner;
        $this->full = $permissions->contains('full-activity');
        $this->list = $permissions->contains('list-activity');
        $this->listAll = $permissions->contains('list_all-activity');
        $this->view = $permissions->contains('view-activity');
        $this->viewAll = $permissions->contains('view_all-activity');
        $this->create = $permissions->contains('create-activity');
        $this->update = $permissions->contains('update-activity');
        $this->delete = $permissions->contains('delete-activity');
    }

    /**
     * Determine whether the user can view the list of activities.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @return mixed
     */
    function list(User $user) {
        return true;
    }

    /**
     * Determine whether the user can view the full-list of activities.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @return mixed
     */
    public function listAll(User $user)
    {
        return $this->isowner || $this->full || $this->listAll;
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
        if ($activity->creator_id == $user->id) {
            return $activity->client_id == $user->client_id;
        } else {
            return $this->isowner || $this->full || $this->viewAll;
        }
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

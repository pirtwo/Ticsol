<?php

namespace App\Ticsol\Base\Policies;

use App\Ticsol\Components\Models\User;
use App\Ticsol\Components\Models\Team;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
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
        $this->full = $permissions->contains('full-team');
        $this->list = $permissions->contains('list-team');
        $this->view = $permissions->contains('view-team');
        $this->create = $permissions->contains('create-team');
        $this->update = $permissions->contains('update-team');
        $this->delete = $permissions->contains('delete-team');    
    }
    
    /**
     * Determine whether the user can view any teams.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->isowner || $this->full || $this->list;
    }

    /**
     * Determine whether the user can view the team.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Team  $team
     * @return mixed
     */
    public function view(User $user, Team $team)
    {
        return $team->client_id == $user->client_id;
    }

    /**
     * Determine whether the user can create teams.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->isowner || $this->full || $this->create;
    }

    /**
     * Determine whether the user can update the team.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Team  $team
     * @return mixed
     */
    public function update(User $user, Team $team)
    {
        if ($team->client_id != $user->client_id) {
            return false;
        }

        return $this->isowner || $this->full || $this->update;
    }

    /**
     * Determine whether the user can delete the team.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Team  $team
     * @return mixed
     */
    public function delete(User $user, Team $team)
    {
        if ($team->client_id != $user->client_id) {
            return false;
        }

        return $this->isowner || $this->full || $this->delete;
    }

    /**
     * Determine whether the user can restore the team.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Team  $team
     * @return mixed
     */
    public function restore(User $user, Team $team)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the team.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Team  $team
     * @return mixed
     */
    public function forceDelete(User $user, Team $team)
    {
        //
    }
}

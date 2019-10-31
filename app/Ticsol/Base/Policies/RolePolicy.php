<?php

namespace App\Ticsol\Base\Policies;

use App\Ticsol\Components\Models\User;
use App\Ticsol\Components\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    protected $owner;
    protected $full;
    protected $list;
    protected $view;
    protected $create;
    protected $update;
    protected $delete;

    public function before($user, $ability)
    {          
        $permissions = $user->permissions; 
        $this->owner = $user->isowner;       
        $this->full = $permissions->contains('full-role');
        $this->list = $permissions->contains('list-role');
        $this->view = $permissions->contains('view-role');
        $this->create = $permissions->contains('create-role');
        $this->update = $permissions->contains('update-role');
        $this->delete = $permissions->contains('delete-role');    
    }

    /**
     * Determine whether the user can view the list of role.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Role  $role
     * @return mixed
     */
    function list(User $user) {
        return true;
    }

    /**
     * Determine whether the user can view the role.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Role  $role
     * @return mixed
     */
    public function view(User $user, Role $role)
    {
        return $role->client_id == $user->client_id;
    }

    /**
     * Determine whether the user can create roles.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->owner || $this->full || $this->create;
    }

    /**
     * Determine whether the user can update the role.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Role  $role
     * @return mixed
     */
    public function update(User $user, Role $role)
    {
        if ($role->client_id != $user->client_id) {
            return false;
        }

        return $this->owner || $this->full || $this->update;
    }

    /**
     * Determine whether the user can delete the role.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Role  $role
     * @return mixed
     */
    public function delete(User $user, Role $role)
    {
        if ($role->client_id != $user->client_id) {
            return false;
        }

        return $this->owner || $this->full || $this->delete;
    }
}

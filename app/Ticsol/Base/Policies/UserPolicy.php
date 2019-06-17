<?php

namespace App\Ticsol\Base\Policies;

use App\Ticsol\Components\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
            $this->full = $role->permissions->contains('name', 'full-user');
            $this->list = $role->permissions->contains('name', 'list-user');
            $this->view = $role->permissions->contains('name', 'view-user');
            $this->create = $role->permissions->contains('name', 'create-user');
            $this->update = $role->permissions->contains('name', 'update-user');
            $this->delete = $role->permissions->contains('name', 'delete-user');
        }
    }

    /**
     * Determine whether the user can view the list of users.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @return mixed
     */
    function list(User $user) {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        return $user->client_id == $model->client_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->full || $this->create;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        if ($model->client_id != $user->client_id) {
            return false;
        }

        return $user->id == $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        return false;
    }

    /**
     * Determine whether the user can activate/deactivate the model.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function activation(User $user, User $model)
    {
        if ($model->client_id != $user->client_id) {
            return false;
        }

        return $this->full || $this->update;
    }
}

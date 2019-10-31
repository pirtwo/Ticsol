<?php

namespace App\Ticsol\Base\Policies;

use App\Ticsol\Components\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
        $this->full = $permissions->contains('full-job');
        $this->list = $permissions->contains('list-job');
        $this->view = $permissions->contains('view-job');
        $this->create = $permissions->contains('create-job');
        $this->update = $permissions->contains('update-job');
        $this->delete = $permissions->contains('delete-job');        
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
        return $this->isowner || $this->full || $this->create;
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

        return $this->isowner || $user->id == $model->id;
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
        return $this->isowner;
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

        return $this->isowner || $this->full || $this->update;
    }
}

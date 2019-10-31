<?php

namespace App\Ticsol\Base\Policies;

use App\Ticsol\Components\Models\User;
use App\Ticsol\Components\Models\Form;
use Illuminate\Auth\Access\HandlesAuthorization;

class FormPolicy
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
     * Determine whether the user can view the list of form.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Form  $form
     * @return mixed
     */
    function list(User $user) {
        return true;
    }

    /**
     * Determine whether the user can view the form.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Form  $form
     * @return mixed
     */
    public function view(User $user, Form $form)
    {
        return $form->client_id == $user->client_id;
    }

    /**
     * Determine whether the user can create forms.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->isowner || $this->full || $this->create;
    }

    /**
     * Determine whether the user can update the form.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Form  $form
     * @return mixed
     */
    public function update(User $user, Form $form)
    {
        if ($form->client_id != $user->client_id) {
            return false;
        }

        return $this->isowner || $this->full || $this->update;
    }

    /**
     * Determine whether the user can delete the form.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Form  $form
     * @return mixed
     */
    public function delete(User $user, Form $form)
    {
        if ($form->client_id != $user->client_id) {
            return false;
        }
        
        return $this->isowner || $this->full || $this->delete;
    }
}

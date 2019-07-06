<?php

namespace App\Ticsol\Base\Policies;

use App\Ticsol\Components\Models\User;
use App\Ticsol\Components\Models\Form;
use Illuminate\Auth\Access\HandlesAuthorization;

class FormPolicy
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
        $permissions = $user->permissions;
        
        $this->full = $permissions->contains('full-job_profile');
        $this->list = $permissions->contains('list-job_profile');
        $this->view = $permissions->contains('view-job_profile');
        $this->create = $permissions->contains('create-job_profile');
        $this->update = $permissions->contains('update-job_profile');
        $this->delete = $permissions->contains('delete-job_profile');    
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
        return $this->full || $this->create;
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

        return $this->full || $this->update;
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
        
        return $this->full || $this->delete;
    }
}

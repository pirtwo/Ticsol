<?php

namespace App\Ticsol\Base\Policies;

use App\Ticsol\Components\Models\Job;
use App\Ticsol\Components\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobPolicy
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
        
        $this->full = $permissions->contains('full-job');
        $this->list = $permissions->contains('list-job');
        $this->view = $permissions->contains('view-job');
        $this->create = $permissions->contains('create-job');
        $this->update = $permissions->contains('update-job');
        $this->delete = $permissions->contains('delete-job');        
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
     * Determine whether the user can view the job.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Job  $job
     * @return mixed
     */
    public function view(User $user, Job $job)
    {
        return $job->client_id == $user->client_id;
    }

    /**
     * Determine whether the user can create jobs.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {        
        return $this->full || $this->create;
    }

    /**
     * Determine whether the user can update the job.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Job  $job
     * @return mixed
     */
    public function update(User $user, Job $job)
    {
        if ($job->client_id != $user->client_id) {
            return false;
        }

        return $this->full || $this->update;
    }

    /**
     * Determine whether the user can delete the job.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Job  $job
     * @return mixed
     */
    public function delete(User $user, Job $job)
    {
        if ($job->client_id != $user->client_id) {
            return false;
        }

        return $this->full || $this->delete;
    }
}

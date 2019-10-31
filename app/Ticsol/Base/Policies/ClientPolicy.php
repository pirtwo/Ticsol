<?php

namespace App\Ticsol\Base\Policies;

use App\Ticsol\Components\Models\User;
use App\Ticsol\Components\Models\Client;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
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
     * Determine whether the user can view any clients.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the client.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\App\Ticsol\Components\Models\Client  $client
     * @return mixed
     */
    public function view(User $user, Client $client)
    {
        return $client->id == $user->client_id;
    }

    /**
     * Determine whether the user can create clients.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the client.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\App\Ticsol\Components\Models\Client  $client
     * @return mixed
     */
    public function update(User $user, Client $client)
    {
        if ($client->id != $user->client_id) {
            return false;
        }

        return $this->isowner || $this->full || $this->update;
    }

    /**
     * Determine whether the user can delete the client.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\App\Ticsol\Components\Models\Client  $client
     * @return mixed
     */
    public function delete(User $user, Client $client)
    {
        //
    }

    /**
     * Determine whether the user can restore the client.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\App\Ticsol\Components\Models\Client  $client
     * @return mixed
     */
    public function restore(User $user, Client $client)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the client.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\App\Ticsol\Components\Models\Client  $client
     * @return mixed
     */
    public function forceDelete(User $user, Client $client)
    {
        //
    }
}

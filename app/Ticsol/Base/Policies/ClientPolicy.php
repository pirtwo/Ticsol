<?php

namespace App\Ticsol\Base\Policies;

use App\Ticsol\Components\Models\User;
use App\Ticsol\Components\Models\Client;
use App\Ticsol\Base\Policies\Traits\PolicyHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    use HandlesAuthorization, PolicyHelper;
    
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
        return $this->checkClient($client, $user);
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
        return $this->checkClient($client, $user) && $this->isOwner($user);
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

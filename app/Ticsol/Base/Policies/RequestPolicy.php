<?php

namespace App\Ticsol\Base\Policies;

use App\Ticsol\Components\Models\User;
use App\Request;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the request.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Request  $request
     * @return mixed
     */
    public function view(User $user, Request $request)
    {
        //
    }

    /**
     * Determine whether the user can create requests.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the request.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Request  $request
     * @return mixed
     */
    public function update(User $user, Request $request)
    {
        //
    }

    /**
     * Determine whether the user can delete the request.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Request  $request
     * @return mixed
     */
    public function delete(User $user, Request $request)
    {
        //
    }
}

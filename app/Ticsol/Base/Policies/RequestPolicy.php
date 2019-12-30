<?php

namespace App\Ticsol\Base\Policies;

use App\Ticsol\Components\Models\Request;
use App\Ticsol\Components\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the list of requests.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @return mixed
     */
    function list(User $user) {
        return true;
    }    

    /**
     * Determine whether the user can view the request.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Request  $request
     * @return mixed
     */
    public function view(User $user, Request $request)
    {
        if ($request->client_id != $user->client_id) {
            return false;
        }

        return $request->user_id == $user->id || $request->assigned_id == $user->id;
    }

    /**
     * Determine whether the user can create requests.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the request.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Request  $request
     * @return mixed
     */
    public function update(User $user, Request $request)
    {
        if ($request->client_id != $user->client_id) {
            return false;
        }

        return $user->id == $request->user_id;
    }

    /**
     * Determine whether the user can delete the request.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Request  $request
     * @return mixed
     */
    public function delete(User $user, Request $request)
    {
        //
    }

    /**
     * Determine whether the user can approve the request.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Request  $request
     * @return mixed
     */
    public function approve(User $user, Request $request)
    {
        $canApproveLeave = false;
        $canApproveReimbursement = false;

        $permissions = $user->permissions;
        $canApproveLeave = $permissions->contains('approve-leave');  
        $canApproveReimbursement = $permissions->contains('approve-reimbursement');      

        if ($user->client_id != $request->client_id) {
            return false;
        }       
        
        if ($user->id != $request->assigned_id) {
            return false;
        }          

        if ($request->type === 'leave') {
            return $canApproveLeave;
        }

        if ($request->type === 'reimbursement') {
            return $canApproveReimbursement;
        }

        return false;
    }

    /**
     * Determine whether the user can reject the request.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Request  $request
     * @return mixed
     */
    public function reject(User $user, Request $request)
    {
        $canRejectLeave = false;
        $canRejectReimbursement = false;

        $permissions = $user->permissions;
        $canRejectLeave = $permissions->contains('reject-leave');  
        $canRejectReimbursement = $permissions->contains('reject-reimbursement');      

        if ($user->client_id != $request->client_id) {
            return false;
        }       
        
        if ($user->id != $request->assigned_id) {
            return false;
        }          

        if ($request->type === 'leave') {
            return $canRejectLeave;
        }

        if ($request->type === 'reimbursement') {
            return $canRejectReimbursement;
        }

        return false;
    }
}

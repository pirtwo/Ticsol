<?php

namespace App\Ticsol\Base\Policies\Traits;

trait PolicyHelper
{
    /**
     * check to see the user is owner or not.
     * 
     * @return bool
     */
    public function isOwner($user)
    {
      return $user->isowner === 1;
    }

    /**
     * matches user client_id with current client id.
     *
     * @return bool
     */
    public function checkClient($client, $user)
    {
        return $client->id === $user->client_id;
    }

    /**
     * matches model creator_id with user_id.
     *
     * @return bool
     */
    public function checkCreator($user, $model)
    {
        return $user->id === $model->creator_id;
    }

    /**
     * matches model user_id with current user id.
     *
     * @return bool
     */
    public function checkUser($user, $model)
    {
        return $user->id === $model->user_id;
    }
}

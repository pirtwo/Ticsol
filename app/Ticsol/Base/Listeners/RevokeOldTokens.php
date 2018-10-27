<?php

namespace App\Ticsol\Components\Base\Listeners;

use Laravel\Passport\Events\AccessTokenCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB; 

class RevokeOldTokens
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Event  $event
     * @return void
     */
    public function handle(AccessTokenCreated $event)
    {        
        //$event contains: tokenId, userId, clientId
        DB::table('oauth_access_tokens')
            ->where('client_id', $event->clientId)
            ->Where('user_id', $event->userId)
            ->where('id', '!=', $event->tokenId)
            ->update(['revoked' => true]);
    }
}

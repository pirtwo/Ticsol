<?php

namespace App\Ticsol\Components\Base\Listeners;

use Laravel\Passport\Events\RefreshTokenCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB; 

class RevokeOldRefreshTokens
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
    public function handle(RefreshTokenCreated $event)
    {        
        // $event contains: accessTokenId, refreshTokenId
        $userId = DB::table('oauth_access_tokens')        
        ->where('oauth_access_tokens.id', '=', $event->accessTokenId)
        ->get()[0]->user_id;

        DB::table('oauth_refresh_tokens')
        ->join('oauth_access_tokens', 'oauth_refresh_tokens.access_token_id', '=', 'oauth_access_tokens.id')
        ->where('oauth_access_tokens.user_id', '=', $userId)
        ->where('oauth_refresh_tokens.id', '!=', $event->refreshTokenId)
        ->update(['oauth_refresh_tokens.revoked' => true]);
        
    }
}

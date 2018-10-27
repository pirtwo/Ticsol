<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // 'App\Events\Event' => [
        //     'App\Listeners\EventListener',
        // ],
        'App\Ticsol\Components\Events\InvitationCreated' => [
            'App\Ticsol\Components\Listeners\InvitationCreated',
        ],
        'Laravel\Passport\Events\AccessTokenCreated' => [
            'App\Ticsol\Components\Base\Listeners\RevokeOldTokens',
        ],
        'Laravel\Passport\Events\RefreshTokenCreated' => [
            'App\Ticsol\Components\Base\Listeners\RevokeOldRefreshTokens',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}

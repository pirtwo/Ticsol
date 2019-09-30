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

        'Spatie\WebhookServer\Events\WebhookCallSucceededEvent' => [
            'App\Ticsol\Components\Webhook\Listeners\WebhookCallSuccess'
        ],

        'Spatie\WebhookServer\Events\WebhookCallFailedEvent' => [
            'App\Ticsol\Components\Webhook\Listeners\WebhookCallFail'
        ],

        // user event listeners
        'App\Ticsol\Components\User\Events\UserCreated' => [
            'App\Ticsol\Components\User\Listeners\SendInvitationEmail'
        ],

        // request event listeners
        'App\Ticsol\Components\Request\Events\RequestApproved' => [
            'App\Ticsol\Components\Request\Listeners\SendApprovedEmail'
        ],
        'App\Ticsol\Components\Request\Events\RequestRejected' => [
            'App\Ticsol\Components\Request\Listeners\SendRejectedEmail'
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

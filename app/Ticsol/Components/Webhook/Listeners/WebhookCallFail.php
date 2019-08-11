<?php

namespace App\Ticsol\Components\Webhook\Listeners;

use Spatie\WebhookServer\Events;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class WebhookCallFail
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
    public function handle(Events\WebhookCallFailedEvent $event)
    {        
        Log::emergency("====== webhook failed =====");
        
        Log::emergency(\json_encode($event));    
    }
}

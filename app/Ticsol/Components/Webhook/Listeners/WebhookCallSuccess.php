<?php

namespace App\Ticsol\Components\Webhook\Listeners;

use Spatie\WebhookServer\Events;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class WebhookCallSuccess
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
    public function handle(Events\WebhookCallSucceededEvent $event)
    {        
        
        Log::emergency("====== webhook success =====");
        
        Log::emergency(\json_encode($event));       
        
    }
}

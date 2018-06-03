<?php

namespace App\Ticsol\Components\Invitation\Listeners;

use App\Events\Event;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

use App\Ticsol\Components\Invitation\Events\Created;
use App\Ticsol\Components\Invitation\Mail\InvitationEmail;

class OnCreation
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
    public function handle(Created $event)
    {        
        Mail::to($event->invitation->email)->send(new InvitationEmail($event->invitation));      
    }
}

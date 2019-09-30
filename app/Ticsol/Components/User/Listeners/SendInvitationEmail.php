<?php

namespace App\Ticsol\Components\User\Listeners;

use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Ticsol\Components\User\Events;
use App\Ticsol\Components\User\Mails;

class SendInvitationEmail implements ShouldQueue
{
    public $tries = 3;
    
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
     * @param  object  $event
     * @return void
     */
    public function handle(Events\UserCreated $event)
    {
        Mail::to($event->user->email)
            ->send(new Mails\Invitation($event->user, $event->pass));
    }
}

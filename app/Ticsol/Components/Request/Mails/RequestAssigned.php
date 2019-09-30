<?php

namespace App\Ticsol\Components\Request\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestAssigned extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('app.ticsol.com.au')
            ->subject('TicSol Invitation')
            ->view('emails.invitation')
            ->with([
                'title' => 'TicSol Invitation',
                'firstname' => $this->user->firstname,
                'username' => $this->user->name,
                'password' => $this->pass
            ]);
    }
}

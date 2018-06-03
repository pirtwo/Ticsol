<?php

namespace App\Ticsol\Components\Invitation\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Ticsol\Components\Models\Invitation;

class InvitationEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $invitation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {        
        return $this
            ->from('Ticsol@info.com')
            ->subject('Ticsol Invitation')
            ->view('emails.invitation')
            ->with([
                'title' => 'Ticsol Invitation',
                'email' => $this->invitation->email,
                'url' => 'https://server.dev/register',
                'token' => $this->invitation->token,
            ]);
    }
}

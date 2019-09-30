<?php

namespace App\Ticsol\Components\User\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Ticsol\Components\Models\User;

class Invitation extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $pass;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->pass = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('ticsol.com.au@mailgun.org')
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

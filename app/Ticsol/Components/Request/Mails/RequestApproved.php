<?php

namespace App\Ticsol\Components\Request\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Ticsol\Components\Models\Request;

class RequestApproved extends Mailable
{
    use Queueable, SerializesModels;

    protected $req;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->req = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from(env('EMAIL_NOREPLY'))
            ->subject('Request Approved')
            ->view('emails.request')
            ->with([
                'title' => 'Request Approved',
                'firstname' => $this->req->user->firstname,
                'requestType' => $this->req->type,
                'requestStatus' => $this->req->status,
                'requestId' => $this->req->id
            ]);
    }
}

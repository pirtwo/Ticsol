<?php

namespace App\Ticsol\Components\Request\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Ticsol\Components\Models\Request;
use App\Ticsol\Components\Models\User;

class RequestCreated extends Notification
{
    use Queueable;
    protected $user = null;
    protected $request = null;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Request $req)
    {
        $this->user = $user;
        $this->request = $req;        
        $this->request->load(['assigned', 'job', 'schedule']);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'req_id' => $this->request->id,
            'req_type' => $this->request->type,
        ]);
    }
}

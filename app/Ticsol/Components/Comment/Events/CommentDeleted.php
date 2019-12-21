<?php

namespace App\Ticsol\Components\Comment\Events;

use App\Ticsol\Components\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentDeleted implements ShouldBroadcast
{
    use SerializesModels;

    protected $clientId;
    protected $commentId;    

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($commentId, $clientId)
    {
        $this->clientId = $clientId;
        $this->commentId = $commentId;
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'Client.Update';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'resName' => 'comment',
            'id' => $this->commentId,
            'title' => "Comment {$this->commentId} deleted successfuly."
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.Clients.' . $this->clientId);
    }
}

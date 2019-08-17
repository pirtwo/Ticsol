<?php

namespace App\Ticsol\Components\Schedule\Events;

use App\Ticsol\Components\Models\User;
use App\Ticsol\Components\Models\Schedule;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ScheduleCreated implements ShouldBroadcastNow
{
    use SerializesModels;

    protected $user;
    protected $type;
    protected $schedule;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $type, Schedule $schedule = null)
    {
        $this->user = $user;
        $this->type = $type;
        $this->schedule = $schedule;
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
            'resName' => 'schedule',
            'id' => $this->type == 'scheduled' ? $this->schedule->id : $this->user->id,
            'title' => $this->type == 'scheduled' ? 'New schedule created.' : 'Unavailable hours created',
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.Clients.' . $this->user->client_id);
    }
}

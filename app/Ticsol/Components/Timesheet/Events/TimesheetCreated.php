<?php

namespace App\Ticsol\Components\Timesheet\Events;

use App\Ticsol\Components\Models\User;
use App\Ticsol\Components\Models\Timesheet;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TimesheetCreated implements ShouldBroadcast
{
    use SerializesModels;
    
    protected $timesheet;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Timesheet $timesheet)
    {
        $this->timesheet = $timesheet;
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'User.Update';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'resName' => 'timesheet',
            'id' => $this->timesheet->id,
            'assigned_id' => $this->timesheet->request->assigned_id,
            'title' => 'Timesheet has been created.',
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        if ($this->timesheet->request->assigned_id != null) {
            return [
                new PrivateChannel('App.Users.' . $this->timesheet->creator_id),
                new PrivateChannel('App.Users.' . $this->timesheet->request->assigned_id),
            ];
        } else {
            return [
                new PrivateChannel('App.Users.' . $this->timesheet->creator_id)
            ];
        }     
    }
}

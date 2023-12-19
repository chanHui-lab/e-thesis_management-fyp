<?php

namespace App\Events;
use Illuminate\Foundation\Events\Dispatchable;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Console\Commands\LaravelLog;

class DeadlineApproachingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $submission;

    public function __construct($submission)
    {
        $this->submission = $submission;
    }
    /**
     * Create a new event instance.
     */
    // public function __construct()
    // {
    //     //
    // }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}

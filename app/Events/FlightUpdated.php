<?php

namespace App\Events;

use App\Models\Flights;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FlightUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Flights $flight;
    /**
     * Create a new event instance.
     */
    public function __construct(Flights $flight)
    {
      $this->flight = $flight;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
          new Channel('flights'),
          new PrivateChannel('flights-private.' . $this->flight->user_id),
        ];
    }
}

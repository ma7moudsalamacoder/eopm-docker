<?php

namespace Modules\Order\Events;

use Modules\Order\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderItemCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Order $order;
    /**
     * Create a new event instance.
     */
    public function __construct(Order $order_) {
        $this->order = $order_;
    }

    /**
     * Get the channels the event should be broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}

<?php

namespace Modules\Order\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Events\OrderDeletedEvent;

class OrderDeletedListener
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(OrderDeletedEvent $event): void {
        try {
            $order = $event->order;
            $user = $order->user;
            activity()
            ->causedBy($user)
            ->log("Order No.".$order->id." has been deleted");
        
        } catch (\Exception $e) {
            Log::error('Error handling OrderItemDeletedEvent: ' . $e->getMessage());
        }
    }
}

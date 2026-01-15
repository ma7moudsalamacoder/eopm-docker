<?php

namespace Modules\Order\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Events\OrderItemDeletedEvent;

class OrderItemDeletedListener
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(OrderItemDeletedEvent $event): void {
        try {
            $orderItem = $event->orderItem;
            $user = auth('api')->user();
            activity()
            ->causedBy($user)
            ->log("Order Item No.".$orderItem->id." has been deleted");
        
        } catch (\Exception $e) {
            Log::error('Error handling OrderItemDeletedEvent: ' . $e->getMessage());
        }
    }
}

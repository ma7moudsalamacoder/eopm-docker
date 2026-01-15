<?php

namespace Modules\Order\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Events\AddOrderItemEvent;
use Modules\Order\Events\OrderItemCreatedEvent;

class OrderItemCreatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(OrderItemCreatedEvent $event): void {
        try {
            $order = $event->order;
            $user = $order->user;
            activity()
            ->causedBy($user)
            ->log('Order item has been added to order No.'.$order->id);
        
        } catch (\Exception $e) {
            Log::error('Error handling AddOrderItemEvent: ' . $e->getMessage());
        }
    }
}

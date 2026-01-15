<?php

namespace Modules\Order\Listeners;


use Illuminate\Support\Facades\Log;
use Modules\Order\Events\AddOrderEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Events\OrderCreatedEvent;

class OrderCreatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(OrderCreatedEvent $event): void {
        try {
            $order = $event->order;
            $user = $order->user;
            activity()
            ->causedBy($user)
            ->log('New order added and it has No.'.$order->id);
        
        } catch (\Exception $e) {
            Log::error('Error handling AddOrderEvent: ' . $e->getMessage());
        }
    }
}

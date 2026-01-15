<?php

namespace Modules\Order\Providers;


use Modules\Order\Events\OrderCreatedEvent;

use Modules\Order\Events\OrderDeletedEvent;
use Modules\Order\Events\OrderCancelledEvent;
use Modules\Order\Events\OrderItemCreatedEvent;
use Modules\Order\Events\OrderItemDeletedEvent;
use Modules\Order\Listeners\OrderCreatedListener;
use Modules\Order\Listeners\OrderDeletedListener;
use Modules\Order\Listeners\OrderCancelledListener;
use Modules\Order\Listeners\OrderItemCreatedListener;
use Modules\Order\Listeners\OrderItemDeletedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        OrderCreatedEvent::class => [
            OrderCreatedListener::class,
        ],
        OrderItemCreatedEvent::class => [
            OrderItemCreatedListener::class,
        ],
        OrderDeletedEvent::class => [
            OrderDeletedListener::class,
        ],
        OrderItemDeletedEvent::class => [
            OrderItemDeletedListener::class,
        ],
        OrderCancelledEvent::class => [
            OrderCancelledListener::class,
        ],
        
    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void {}
}

<?php

namespace Modules\Auth\Providers;

use Modules\Auth\Events\UserLoggedInEvent;
use Modules\Auth\Events\UserRegisteredEvent;
use Modules\Auth\Listeners\UserLoggedInListener;
use Modules\Auth\Listeners\UserRegisteredListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        UserLoggedInEvent::class => [
            UserLoggedInListener::class,
        ],
        UserRegisteredEvent::class => [
            UserRegisteredListener::class,
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

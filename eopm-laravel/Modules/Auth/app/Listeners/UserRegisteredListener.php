<?php

namespace Modules\Auth\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Auth\Events\UserRegisteredEvent;
use Modules\Auth\Notifications\UserRegisteredNotification;

class UserRegisteredListener
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(UserRegisteredEvent  $event): void {
        try {
            $user = $event->user;
            $user->notify(new UserRegisteredNotification());
            activity()
            ->causedBy($user)
            ->log('New user registered: ' . $user->email);
        
        } catch (\Exception $e) {
            Log::error('Error handling UserRegisteredEvent: ' . $e->getMessage());
        }
    }
}

<?php

namespace Modules\Auth\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Auth\Events\UserLoggedInEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserLoggedInListener
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(UserLoggedInEvent $event): void {
        try {
            $user = $event->user;
            activity()
            ->causedBy($user)
            ->log('User logged in, all previous sessions invalidated');
        
        } catch (\Exception $e) {
            Log::error('Error handling UserLoggedInEvent: ' . $e->getMessage());
        }
    }
}

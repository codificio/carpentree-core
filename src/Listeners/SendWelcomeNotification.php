<?php

namespace Illuminate\Auth\Listeners;

use Carpentree\Core\Traits\IsWelcome;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class SendWelcomeNotification
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if (!in_array(IsWelcome::class, class_uses_recursive(get_class($event->user)))) {
            return;
        }

        if ($event->user instanceof MustVerifyEmail && ! $event->user->hasVerifiedEmail()) {
            $event->user->sendWelcomeNotificationWithEmailVerification();
        } else {
            $event->user->sendWelcomeNotification();
        }
    }
}


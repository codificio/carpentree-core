<?php

namespace Carpentree\Core\Listeners;

use Carpentree\Core\Models\User;
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
        /** @var User $user */
        $user = $event->user;

        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            $user->sendWelcomeNotificationWithEmailVerification();
        } else {
            $user->sendWelcomeNotification();
        }
    }
}


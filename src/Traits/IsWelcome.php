<?php

namespace Carpentree\Core\Traits;

trait IsWelcome
{
    /**
     * Send the welcome email notification.
     *
     * @return void
     */
    public function sendWelcomeNotification()
    {
        $this->notify($this);
    }

    /**
     * Send the welcome email notification with email verification
     *
     * @return void
     */
    public function sendWelcomeNotificationWithEmailVerification()
    {
        $this->notify($this);
    }
}

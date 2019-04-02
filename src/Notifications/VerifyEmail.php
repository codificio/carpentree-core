<?php

namespace Carpentree\Core\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail as ParentNotifications;

class VerifyEmail extends ParentNotifications
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }

        return (new MailMessage)
            ->subject(__('Verify Email Address'))
            ->view('carpentree-core::emails.auth.verify-email', [
                'verificationUrl' => $this->verificationUrl($notifiable)
            ]);
    }
}

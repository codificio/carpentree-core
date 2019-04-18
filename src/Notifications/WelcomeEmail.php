<?php

namespace Carpentree\Core\Notifications;

use Carpentree\Core\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * @property  User user
 */
class WelcomeEmail extends Notification
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        return (new MailMessage)
            ->subject(__('Welcome!'))
            ->view('carpentree-core::emails.auth.welcome-email', [
                'user' => $this->user
            ]);
    }

}

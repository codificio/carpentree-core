<?php

namespace Carpentree\Core\Notifications;

use Carpentree\Core\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;

/**
 * @property  User user
 */
class WelcomeEmail extends Notification
{
    use Queueable;

    /**
     * Create a notification instance.
     *
     * @param  User  $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

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

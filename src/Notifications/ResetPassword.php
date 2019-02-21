<?php

namespace Carpentree\Core\Notifications;

use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as ParentNotification;

class ResetPassword extends ParentNotification
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
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return (new MailMessage)
            ->subject(Lang::getFromJson('Reset Password Notification'))
            ->line(Lang::getFromJson('You are receiving this email because we received a password reset request for your account.'))
            ->action(Lang::getFromJson('Reset Password'), $this->resetPasswordUrl())
            ->line(Lang::getFromJson('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.users.expire')]))
            ->line(Lang::getFromJson('If you did not request a password reset, no further action is required.'));
    }

    protected function resetPasswordUrl()
    {
        return url("password/reset?token={$this->token}");
    }

}

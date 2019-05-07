<?php

namespace Carpentree\Core\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The user email.
     *
     * @var string
     */
    public $email;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
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
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token, $this->email);
        }

        return (new MailMessage)
            ->subject(__('Reset Password Notification'))
            ->view('carpentree-core::emails.auth.reset-password', [
                'resetPasswordUrl' => $this->resetPasswordUrl(),
                'expiresIn' => config('auth.passwords.users.expire')
            ]);
    }

    protected function resetPasswordUrl()
    {
        return url("password/reset?token={$this->token}&email={$this->email}");
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }

}

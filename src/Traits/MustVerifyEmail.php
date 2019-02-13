<?php

namespace Carpentree\Core\Traits;

use Carpentree\Core\Notifications\VerifyEmail;
use Illuminate\Auth\MustVerifyEmail as ParentTrait;

trait MustVerifyEmail
{
    use ParentTrait;

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }
}

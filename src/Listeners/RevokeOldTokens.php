<?php

namespace Carpentree\Core\Listeners;

use Carpentree\Core\Models\User;
use Laravel\Passport\Events\AccessTokenCreated;

class RevokeOldTokens
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AccessTokenCreated $event
     * @return void
     */
    public function handle(AccessTokenCreated $event)
    {
        $user = User::find($event->userId);

        foreach ($user->tokens as $token) {
            if ($token->id != $event->tokenId) {
                $token->revoke();
            }
        }
    }
}

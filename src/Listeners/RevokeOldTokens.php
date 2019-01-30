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

        $tokens = $user->tokens()
            ->where('id', '<>', $event->tokenId)
            ->where('client_id', $event->clientId)
            ->get();

        foreach ($tokens as $token) {
            $token->revoke();
        }
    }
}

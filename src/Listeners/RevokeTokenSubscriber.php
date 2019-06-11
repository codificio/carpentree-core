<?php


namespace Carpentree\Core\Listeners;

use Carpentree\Core\Events\UserDeleted;
use Illuminate\Support\Facades\DB;

class RevokeTokenSubscriber
{

    public function onUserDelete($event)
    {
        DB::table('oauth_access_tokens')
            ->where('user_id', $event->userId)
            ->update(['revoked' => true]);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            UserDeleted::class,
            'Carpentree\Core\Listeners\RevokeTokenSubscriber@onUserDelete'
        );
    }
}

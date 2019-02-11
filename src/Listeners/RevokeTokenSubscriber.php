<?php


namespace Carpentree\Core\Listeners;

use Carpentree\Core\Events\UserDeleted;
use Carpentree\Core\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Events\AccessTokenCreated;

class RevokeTokenSubscriber
{
    /** @var UserRepository */
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->userRepository->skipCache(true);
    }

    public function onUserDelete($event)
    {
        DB::table('oauth_access_tokens')
            ->where('user_id', $event->userId)
            ->update(['revoked' => true]);
    }

    public function onAccessTokenCreate($event)
    {
        $user = $this->userRepository->find($event->userId);

        $tokens = $user->tokens()
            ->where('id', '<>', $event->tokenId)
            ->where('client_id', $event->clientId)
            ->get();

        foreach ($tokens as $token) {
            $token->revoke();
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            AccessTokenCreated::class,
            'Carpentree\Core\Listeners\RevokeTokenSubscriber@onAccessTokenCreate'
        );

        $events->listen(
            UserDeleted::class,
            'Carpentree\Core\Listeners\RevokeTokenSubscriber@onUserDelete'
        );
    }
}

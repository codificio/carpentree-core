<?php

namespace Carpentree\Core\Providers;

use Carpentree\Core\Listeners\PruneOldTokens;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Laravel\Passport\Events\RefreshTokenCreated' => [
            PruneOldTokens::class,
        ],
        'Illuminate\Auth\Events\Registered' => [
            'Carpentree\Core\Listeners\SendWelcomeNotification',
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'Carpentree\Core\Listeners\RevokeTokenSubscriber',
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}

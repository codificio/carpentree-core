<?php

namespace Carpentree\Core\Providers;

use Carpentree\Core\Listeners\PruneOldTokens;
use Carpentree\Core\Listeners\RevokeOldTokens;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Laravel\Passport\Events\AccessTokenCreated' => [
            RevokeOldTokens::class,
        ],

        'Laravel\Passport\Events\RefreshTokenCreated' => [
            PruneOldTokens::class,
        ],
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

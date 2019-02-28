<?php

namespace Carpentree\Core\Providers;

use Carpentree\Core\Scout\Engines\LocalizedAlgoliaEngine;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Laravel\Scout\EngineManager;
use Algolia\AlgoliaSearch\SearchClient as Algolia;

class LocaleServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {

        // Scout Engines
        resolve(EngineManager::class)->extend('localized-algolia', function () {
            return new LocalizedAlgoliaEngine(Algolia::create(
                config('scout.algolia.id'), config('scout.algolia.secret')
            ));
        });

    }
}



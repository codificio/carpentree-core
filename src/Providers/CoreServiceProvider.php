<?php

namespace Carpentree\Core\Providers;

use Barryvdh\Cors\HandleCors;
use Carpentree\Core\Console\Commands\RefreshPermissions;
use Carpentree\Core\Services\SocialUserResolver;
use Hivokas\LaravelPassportSocialGrant\Resolvers\SocialUserResolverInterface;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{

    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        SocialUserResolverInterface::class => SocialUserResolver::class,
    ];

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMigrations();

        $this->mapRoutes();

        $this->publishConfig();

        $this->publishResources();

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        // Views
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'carpentree');

        // Middlewares
        /** @var Router $router */
        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('api', HandleCors::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/core.php',
            'carpentree.core'
        );

        $this->mergeConfigFrom(
            __DIR__.'/../../config/permissions.php',
            'carpentree.permissions'
        );
    }

    public function publishResources()
    {
        $this->publishes([
            __DIR__ . '/../../resources/assets' => resource_path('assets/vendor/carpentree/core')
        ], 'assets');
    }

    public function publishConfig()
    {
        $this->publishes([
            __DIR__.'/../../config/core.php' => config_path('carpentree/core.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../../config/permissions.php' => config_path('carpentree/permissions.php'),
        ], 'config');
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapRoutes()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/api/admin.php');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../../routes/auth.php');
    }

    /**
     * Register Module's migration files.
     */
    protected function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Registering package commands.
        $this->commands([
            RefreshPermissions::class
        ]);
    }
}

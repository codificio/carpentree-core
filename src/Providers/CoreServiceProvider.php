<?php

namespace Carpentree\Core\Providers;

use Barryvdh\Cors\HandleCors;
use Carpentree\Core\Builders\Address\AddressBuilder;
use Carpentree\Core\Builders\Address\AddressBuilderInterface;
use Carpentree\Core\Console\Commands\RefreshPermissions;
use Carpentree\Core\Builders\User\UserBuilder;
use Carpentree\Core\Builders\User\UserBuilderInterface;
use Carpentree\Core\DataAccess\User\EloquentUserDataAccess;
use Carpentree\Core\DataAccess\User\UserDataAccess;
use Carpentree\Core\Models\User;
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

        $this->publish();

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        // Views
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'carpentree-core');

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

        $this->mergeConfigFrom(
            __DIR__.'/../../config/roles.php',
            'carpentree.roles'
        );

        $this->mergeConfigFrom(
            __DIR__.'/../../config/repository.php',
            'carpentree.repository'
        );

        $this->bindImplementation();
    }

    public function bindImplementation()
    {
        // User Builder Service
        $this->app->bind(UserBuilderInterface::class, UserBuilder::class);

        // Address Builder Service
        $this->app->bind(AddressBuilderInterface::class, AddressBuilder::class);

        // User Data Access
        $this->app->bind(UserDataAccess::class, function () {
            return new EloquentUserDataAccess(User::class);
        });

        // RequestCriteria Statement builder
        $this->app->bind('Carpentree\Core\Repositories\Contracts\StatementBuilderInterface',
            'Carpentree\Core\Repositories\Criteria\Request\DimsavStatementBuilder');

        // User Repository
        $this->app->bind('Carpentree\Core\Repositories\UserRepository', 'Carpentree\Core\Repositories\UserRepositoryEloquent');
    }

    public function publish()
    {
        // Views
        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/carpentree-core')
        ], 'views');

        // Config
        $this->publishes([
            __DIR__.'/../../config/core.php' => config_path('carpentree/core.php'),
            __DIR__.'/../../config/permissions.php' => config_path('carpentree/permissions.php'),
            __DIR__.'/../../config/roles.php' => config_path('carpentree/roles.php'),
            __DIR__.'/../../config/repository.php' => config_path('carpentree/repository.php'),
        ], 'config');

        // Migrations
        $this->publishes([
            __DIR__.'/../../database/migrations/' => database_path('migrations')
        ], 'migrations');
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
        $this->loadRoutesFrom(__DIR__.'/../../routes/api/base.php');
        $this->loadRoutesFrom(__DIR__.'/../../routes/api/admin.php');
        // $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
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
            RefreshPermissions::class,
        ]);
    }
}

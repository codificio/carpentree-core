<?php

namespace Carpentree\Core\Providers;

use Carpentree\Core\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(
            function ($user, $ability) {
                /** @var User $user */
                if ($user->isSuperAdmin()) {
                    return true;
                }

                return null;
            });

        Passport::tokensCan([
            'admin' => 'Enter to the admin panel'
        ]);

        // Passport::routes();
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Định nghĩa một Gate cho role "manager"
        Gate::define('is-manager', function ($user) {
            return $user->hasRole('Manager');
        });

        Gate::define('is-customer', function ($user) {
            return $user->hasRole('Customer');
        });

        // Định nghĩa một Gate cho role "admin"
        Gate::define('is-admin', function ($user) {
            return $user->hasRole('Admin');
        });
    }
}

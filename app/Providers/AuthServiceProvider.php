<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isAdmin', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('isStorekeeper', function ($user) {
            return $user->hasRole('storekeeper') || $user->hasRole('admin');
        });

        Gate::define('isAuditor', function ($user) {
            return $user->hasRole('auditor') || $user->hasRole('admin');
        });

        Gate::define('isPrincipal', function ($user) {
            return $user->hasRole('principal') || $user->hasRole('admin');
        });

        Gate::define('isRequester', function ($user) {
            return $user->hasRole('requester') || $user->hasRole('admin');
        });

        Gate::define('accessSra', function ($user) {
            return $user->hasAnyRole(['admin', 'storekeeper', 'auditor', 'principal']);
        });

        Gate::define('accessRequisitions', function ($user) {
            return $user->hasAnyRole(['admin', 'storekeeper', 'principal', 'requester']);
        });

        Gate::define('accessIssues', function ($user) {
            return $user->hasAnyRole(['admin', 'storekeeper']);
        });

        Gate::define('accessReports', function ($user) {
            return $user->hasAnyRole(['admin', 'storekeeper', 'principal', 'auditor']);
        });

        Gate::define('accessLedger', function ($user) {
            return $user->hasAnyRole(['admin', 'storekeeper', 'principal', 'auditor']);
        });
    }
}

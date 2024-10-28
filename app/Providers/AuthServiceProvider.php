<?php

namespace App\Providers;

use App\Models\ActivityLog;
use App\Models\Insurance;
use App\Models\User;
use App\Policies\ActivityLogPolicy;
use App\Policies\AgentPolicy;
use App\Policies\InsurancePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => AgentPolicy::class,
        ActivityLog::class => ActivityLogPolicy::class,
        Insurance::class => InsurancePolicy::class,
    ];

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
//        Gate::before(function ($user, $ability) {
//            return $user->hasRole('super_admin') ? true : null;
//        });
    }
}

<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Cita;
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Cita::class => CitaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Implicitly grant "Super Admin" role all permissions.
        // This works in the app by using gate-related functions like auth()->user()->can('administer-roles-and-permissions');
        // or @can('administer-roles-and-permissions')
        //Gate::before(function ($user, $ability) {
        //    return $user->hasRole('Super Admin') ? true : null;
        //});
    }
}
<?php

namespace App\Providers;

use App\Models\Notas;
use App\Policies\NotasPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Notas::class => NotasPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        Gate::define('getNota', [NotasPolicy::class, 'view']);
        Gate::define('viewAnyNotas', [NotasPolicy::class, 'viewAny']);
        Gate::define('createNotas', [NotasPolicy::class, 'create']);
        Gate::define('updateNotas', [NotasPolicy::class, 'update']);
    }
}

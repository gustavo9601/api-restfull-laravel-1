<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Genera las rutas propias de passport
        Passport::routes();

        // Definiendo el tiempo de expiracion de tokens
        // Le definimos la fecha en que expirara el token, al igual al refresh
        Passport::tokensExpireIn(Carbon::now()->addMinutes(1));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));

        // Habilitando el gran type implicito
        // Passport::enableImplicitGrant();
    }
}

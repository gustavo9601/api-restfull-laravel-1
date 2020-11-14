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
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));

        // Habilitando el gran type implicito
        // Passport::enableImplicitGrant();

        // Habilitando Scopes para clientes
        Passport::tokensCan([  // Cada posicion son las habilidades, que podra realizar el cliente
            'purchase-product' => 'Crear transaciones para comprar productos determinados',
            'manage-products' => 'Crear, ver, actualizar y eliminar productos',
            'manage-account' => 'Obtener informacion de la cuenta, nombre, email, estado, modificar datos como email nomrnbre y contraseña',
            'read-general' => 'Obtener informacion, general, categorias donde se compra y vende, productos vendidos o comprados, transacciones, compras, y ventas'
        ]);

    }
}

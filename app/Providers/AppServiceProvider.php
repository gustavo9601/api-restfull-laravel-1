<?php

namespace App\Providers;

use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use App\Product;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Identificando cuando se cree un Usuario disparara el evento
        User::created(function ($user) {
            // Ejecuta el email
            // retry(cantidad_intengetos, funtion(){}, tiempo_de_espera_a_volver_a_ejecutar_milisegundos)
            retry(5, function () use ($user){
                Mail::to($user->email)->send(New UserCreated($user));
            }, 200);
        });

        // Identificamos cuando se actualizce el usuario, especialmente si cambia el correo para enviar el nuevo email
        User::updated(function ($user) {
            // Ejecuta el email, si el correo cambio ya que recibe una instancia del modelo User y tiene acceso a dicha funcion
            if ($user->isDirty('email')) {
                retry(5, function () use ($user){
                    Mail::to($user->email)->send(new UserMailChanged($user));
                }, 200);
            }
        });


        // Identificacion que cuando un producto sea actualizado, y ya no cuenta con stock pase a estar en estado no disponible
        Product::updated(function ($product) {
            if ($product->quantity == 0 && $product->estaDisponible()) {
                $product->status = Product::PRODUCTO_NO_DISPONIBLE;
                $product->save();
            }
        });
    }
}

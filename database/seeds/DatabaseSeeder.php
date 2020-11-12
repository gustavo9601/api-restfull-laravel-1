<?php

use App\User;
use App\Product;
use App\Category;
use App\Transaction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);

        $cantidadUsuarios = 20;
        $cantidadCategorias = 30;
        $cantidadProducts = 100;
        $cantidadTransacciones = 500;

        // Omitiendo eventos creados manual mente en el AppServiceProvider para el Modelo en mension
        User::flushEventListeners();
        Category::flushEventListeners();
        Product::flushEventListeners();
        Transaction::flushEventListeners();

        factory(User::class, $cantidadUsuarios)->create();
        factory(Category::class, $cantidadCategorias)->create();

        factory(Product::class, $cantidadProducts)->create()->each(
            function ($product) {
                $categorias = Category::all();  // Obtiene todas las categorias
                $categorias = $categorias->random(mt_rand(1, $categorias->count()))
                    ->pluck('id'); // Selecciona aletarioamente diferentes categorias, y selecciona solo la columnas id

                // Relazara el attach de los categorias al product
                $product->categories()->attach($categorias);
            }
        );

        factory(Transaction::class, $cantidadTransacciones)->create();

    }
}

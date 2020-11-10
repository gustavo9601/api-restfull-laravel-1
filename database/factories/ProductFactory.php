<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use App\Product;
use App\User;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph($faker->numberBetween(1,10)),
        'quantity' => $faker->numberBetween(1,100),
        'status' => $faker->randomElement([Product::PRODUCTO_NO_DISPONIBLE, Product::PRODUCTO_DISPONIBLE]),
        'image' => 'default.png',
        'seller_id' => factory(User::class)->create()
        // 'seller_id' => User::inRandomOrder()->first() // Usando un usuario existente
    ];
});

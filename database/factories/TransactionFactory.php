<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Transaction;
use App\Seller;
use App\User;
use Faker\Generator as Faker;


$factory->define(Transaction::class, function (Faker $faker) {
    // has pregunta en la relacion products, con random optiene uno aletaroio
    $vendedorRandom = Seller::has('products')->get()->random();
    // expect != del id pasado por paramentro, con ramdim optiene uno o varios aletarios
    $compradorRandom = User::all()->except($vendedorRandom->id)->random();

    return [
        'quantity' => $faker->numberBetween(1,100),
        'buyer_id' => $compradorRandom->id,
        'product_id' => $vendedorRandom->products->random()->id // Accede a travez de la relacion products
    ];
});

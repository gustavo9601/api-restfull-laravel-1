<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => 'secret', // password
        'remember_token' => Str::random(10),
        'verified' => $verificado = $faker->randomElement([User::USUARIO_NO_VERIFICADO, User::USUARIO_VERIFICADO]),
        'verification_token' => ($verificado == User::USUARIO_VERIFICADO) ? User::generarVerificationToken() : null,  // Si esta verificado genera el token
        'admin' => $faker->randomElement([User::USUARIO_ADMINISTRADOR, User::USUARIO_REGULAR]),
    ];
});

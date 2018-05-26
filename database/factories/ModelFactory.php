<?php

use App\Ticsol\Components\Models\Client;
use App\Ticsol\Components\Models\User;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'client_id' => $faker->numberBetween(1,3),
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'isowner' => false,
    ];
});

$factory->define(Client::class, function (Faker\Generator $faker) {
    
    return [
        'name' => $faker->company,
        'licences' => json_encode(array( 'type' => 'basic')),        
    ];
});

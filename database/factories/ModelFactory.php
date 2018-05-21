<?php

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
$factory->define(App\Ticsol\Components\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'client_id' => 1,
        'user_name' => $faker->name,
        'user_email' => $faker->unique()->safeEmail,
        'user_password' => $password ?: $password = bcrypt('secret'),
        'user_isowner' => false,
    ];
});

$factory->define(App\Ticsol\Components\Models\Client::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'client_name' => $faker->company,
        'client_licences' => json_encode(array( 'type' => 'basic')),        
    ];
});

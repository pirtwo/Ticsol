<?php

use App\Ticsol\Components\Models\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    static $password;
    $index = $faker->numberBetween(1, 8);
    $firstname = $faker->firstname;
    $lastname = $faker->lastname;

    return [
        'client_id' => $faker->numberBetween(1, 3),
        'name' => $firstname . " " . $lastname,
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),              
        'meta' => [ 'avatar' => str_replace_array('?', [$index], '/img/avatar/pic_?.jpg') ]
    ];
});

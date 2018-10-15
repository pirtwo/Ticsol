<?php

use App\Ticsol\Components\Models\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    static $password;
    $index = $faker->numberBetween(1, 8);

    return [
        'client_id' => $faker->numberBetween(1, 3),
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'isowner' => false,
        'meta' => [ 'avatar' => str_replace_array('?', [$index], '/img/avatar/pic_?.jpg') ]
    ];
});

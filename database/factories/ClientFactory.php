<?php

use App\Ticsol\Components\Models\Client;
use Faker\Generator as Faker;

$factory->define(Client::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'meta' => ['type' => 'basic'],
    ];
});

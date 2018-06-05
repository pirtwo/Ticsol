<?php

use App\Ticsol\Components\Models\Job;
use Faker\Generator as Faker;

$factory->define(Job::class, function (Faker $faker) {
    return [
        'client_id' => $faker->numberBetween(1, 3),
        'creator_id' => $faker->numberBetween(1, 30),
        'parent_id' => null,
        'title' => $faker->jobTitle,
        'code' => $faker->numberBetween(1, 500),
        'isactive' => true,
        'meta' => null,
    ];
});

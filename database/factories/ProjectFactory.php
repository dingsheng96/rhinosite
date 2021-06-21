<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Project;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(),
        'description' => $faker->paragraph(),
        'user_id' => 2,
        'services' => null,
        'materials' => null,
        'currency_id' => 1,
        'unit_price' => $faker->randomNumber(3),
        'unit_id' => 1,
        'unit_value' => $faker->randomNumber(2),
        'on_listing' => $faker->boolean(100)
    ];
});

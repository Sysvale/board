<?php

namespace Database\Factories;


/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Company;
use App\Models\Team;
use Faker\Generator as Faker;

$factory->define(Team::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'board_lists' => [1,2],
        'board_lists.0.name' => $faker->name(),
        'board_lists.1.name' => $faker->name(),
        'board_lists.0.position' => $faker->numberBetween(1,9),
        'board_lists.1.position' => $faker->numberBetween(1,9),
        'lottie_file' => $faker->url(),
    ];
});

$factory->state(Team::class, 'with-company', function() {
    return [
        'company_id' => factory(Company::class)->create()->id,
    ];
});

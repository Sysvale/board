<?php

namespace Database\Factories;


/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Company;
use App\Models\Workspace;
use Faker\Generator as Faker;

$factory->define(Workspace::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'lottie_file' => $faker->url(),
    ];
});

$factory->state(Workspace::class, 'with-company', function() {
    return [
        'company_id' => factory(Company::class)->create()->id,
    ];
});

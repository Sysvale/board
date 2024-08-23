<?php

namespace Database\Factories;


/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Company;
use Faker\Generator as Faker;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'cnpj' => $faker->regexify('[0-9]{15}'),
        'name' => $faker->name(),
        'phone' => $faker->phoneNumber(),
        'email' => $faker->email(),
    ];
});

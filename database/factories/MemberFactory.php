<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Company;
use App\Models\Member;
use Faker\Generator as Faker;

$factory->define(Member::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
		'avatar_url' => $faker->url(),
    ];
});

$factory->state(Member::class, 'with-company', function () {
    return [
        'company_id' => factory(Company::class)->create()->id,
    ];
});

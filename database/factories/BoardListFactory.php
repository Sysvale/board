<?php

namespace Database\Factories;


/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\BoardList;
use Faker\Generator as Faker;

$factory->define(BoardList::class, function (Faker $faker) {
	return [
		'name' => $faker->name,
		'position' => $faker->numberBetween(1, 10),
		'key' => $faker->md5,
		'user_story_holder' => $faker->name,
		'accepts_card_type' => [],
		'team_id' => $faker->md5,
		'is_devlog' => $faker->boolean(50),
		'is_goalable' => $faker->boolean(50),
	];
});

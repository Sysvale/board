<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\BoardList;
use App\Models\Card;
use Faker\Generator as Faker;

$factory->define(Card::class, function (Faker $faker) {
	return [
		'number' => $faker->numberBetween(1, 100),
		'title' => $faker->sentence(),
		'link' => $faker->url(),
		'position' => $faker->numberBetween(1, 100),
		'type' => 'user-story',
	];
});

$factory->state(Card::class, 'with-board', function () {
    return [
        'board_list_id' => factory(BoardList::class)
			->create()
			->id,
    ];
});

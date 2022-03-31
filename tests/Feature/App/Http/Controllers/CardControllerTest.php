<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Card;
use App\User;
use Tests\TestCase;

class CardControllerTest extends TestCase
{
	public function testItIsPossibleToDestroyManyCardsInASingleRequest()
	{
		Card::unsetEventDispatcher();
		$cards_ids = factory(Card::class, 5)->create()->pluck('id')->toArray();

		$this
			->actingAs(factory(User::class)->create())
			->deleteJson(route('cards.destroy_many', ['ids' => $cards_ids]))
			->assertStatus(204);

		$this->assertEmpty(Card::findMany($cards_ids));
	}
}

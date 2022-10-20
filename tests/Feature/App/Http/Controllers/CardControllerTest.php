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
		$cards_ids = factory(Card::class, 5)
			->create()
			->pluck('id')
			->toArray();

		$this
			->actingAs(factory(User::class)->state('with-member-company')->create())
			->deleteJson(route('cards.destroy_many', ['ids' => $cards_ids]))
			->assertStatus(204);

		$this->assertEmpty(Card::findMany($cards_ids));
	}

	public function testIfAuthenticatedUsersCanCreateANewCard()
	{
		//Given we have an authenticated user
		$this->actingAs(
			factory(User::class)
				->state('with-member-company')
				->create()
		);
		//And a card object
		$card = factory(Card::class)->state('with-board')->make();
		//When user submits post request to create card endpoint
		$result = $this->post('cards', $card->toArray());
		//It gets stored in the database
		$this->assertEquals(1, Card::all()->count());
	}
}

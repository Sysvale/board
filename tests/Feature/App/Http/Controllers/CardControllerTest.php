<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Card;
use App\User;
use Tests\TestCase;

class CardControllerTest extends TestCase
{
	public function setUp(): void
	{
		parent::setUp();
		
		$this->user = factory(User::class)->state('with-member-company')->create();
		$this->actingAs($this->user);
	}

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
		$card = factory(Card::class)->state('with-board')->make();

		$response = $this->post('cards', $card->toArray());

		$this->assertEquals(1, Card::all()->count());
		$this->assertEquals(201, $response->status());
	}
}

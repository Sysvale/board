<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\BoardList;
use App\Models\Card;
use App\Models\Company;
use App\Models\CompanyClient;
use App\Models\User;
use Laravel\Passport\Passport;
use Sysvale\Mongodb\Passport\Client;
use Tests\TestCase;
use Lcobucci\JWT\Parser;

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

	public function testAuthenticatedUserCanCreateANewCard()
	{
		$card = factory(Card::class)->state('with-board')->make();

		$response = $this->actingAs(factory(User::class)->state('with-member-company')->create())
			->post('cards', $card->toArray());

		$this->assertEquals(1, Card::all()->count());
		$this->assertEquals(201, $response->status());
	}

	public function testAuthenticatedClientCanCreateCard(): void
	{
		$company = factory(Company::class)->create();
		$board_list = factory(BoardList::class)->create(['company_id' => $company->id]);
		$client = Client::create();

		CompanyClient::create([
			'company_id' => $company->id,
			'client_id' =>  $client->id,
		]);

		Passport::actingAsClient($client);
		$this->mock(Parser::class, function ($mock) {
			$mock->shouldReceive('parse->claims->get')->andReturn('access_token');
		});

		$this->withHeader('Authorization', 'Bearer access_token')
			->postJson('api/cards', [
				'title' => 'Creating card as client',
				'type' => 'user-story',
				'board_list_id' => $board_list->id,
		])
		->assertCreated();
	}

	public function testAuthenticatedClientCanHandleAnBatch(): void
	{
		$company = factory(Company::class)->create();
		$board_list = factory(BoardList::class)->create(['company_id' => $company->id]);
		$client = Client::create();

		CompanyClient::create([
			'company_id' => $company->id,
			'client_id' =>  $client->id,
		]);

		Passport::actingAsClient($client);
		$this->mock(Parser::class, function ($mock) {
			$mock->shouldReceive('parse->claims->get')->andReturn('access_token');
		});

		$user_story_card = factory(Card::class)->create();
		$task_card = factory(Card::class, ['type'=>'task'])->create();
		$task_card->title = 'Change Title';

		$data = [
				"update_attribute" => "integration_metadata.granatum.id",
				"cards" => [
					[
						"title" => $task_card->title,
						"type" =>  "task",
						"board_list_id" => $board_list->id,
						"user_story_id" => $user_story_card->id,
						"integration_metadata" => [
							"granatum" => [
								"id" => "1231y54kh3khiu1g23uh6"
							]
						]
					],
					[
						"title" => "Card criado via api",
						"type" =>  "task",
						"board_list_id" => $board_list->id,
						"user_story_id" => $user_story_card->id,
						"integration_metadata" => [
							"granatum" => [
								"id" => "321wdsfgoiukh32423huls"
							]
						]
					],
				]
		];

		$this->withHeader('Authorization', 'Bearer access_token')
			->postJson('api/cards/batch', $data)
			->assertOk();

		$this->assertDatabaseHas('cards', [
			'title' => 'Change Title',
		]);
		$this->assertDatabaseHas('cards', [
			'integration_metadata.granatum.id' => '321wdsfgoiukh32423huls',
		]);
	}

	public function testAuthenticatedClientCantHandleAnBatchWithWrongKeyType(): void
	{
		$company = factory(Company::class)->create();
		$board_list = factory(BoardList::class)->create(['company_id' => $company->id]);
		$client = Client::create();

		CompanyClient::create([
			'company_id' => $company->id,
			'client_id' =>  $client->id,
		]);

		Passport::actingAsClient($client);
		$this->mock(Parser::class, function ($mock) {
			$mock->shouldReceive('parse->claims->get')->andReturn('access_token');
		});

		$user_story_card = factory(Card::class)->create();

		$data = [
				"update_attribute" => ["integration_metadata.granatum.id"],
				"cards" => [
					[
						"title" => "Card criado via api",
						"type" =>  "task",
						"board_list_id" => $board_list->id,
						"user_story_id" => $user_story_card->id,
						"integration_metadata" => [
							"granatum" => [
								"id" => "321wdsfgoiukh32423huls"
							]
						]
					],
				]
		];

		$this->withHeader('Authorization', 'Bearer access_token')
			->postJson('api/cards/batch', $data)
			->assertStatus(422); // Unprocessable
	}
}

<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Card;
use App\Models\Team;
use App\User;
use Tests\TestCase;

class TeamControllerTest extends TestCase
{
	public function testIfAuthenticatedMemberCanCreateANewTeam()
	{
		//Given we have an authenticated user
		$this->actingAs(
			factory(User::class)
				->state('with-member-company')
				->create()
		);
		//And a card object
		$workspace = factory(Team::class)->state('with-company')->make();

		$data = $workspace->toArray();

		//When user submits post request to create card endpoint
		$result = $this->post('teams', $data);

		dump($result);
		//It gets stored in the database
		$this->assertEquals(1, Team::all()->count());
	}
}

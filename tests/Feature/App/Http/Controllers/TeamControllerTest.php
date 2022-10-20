<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Team;
use App\User;
use Tests\TestCase;

class TeamControllerTest extends TestCase
{
	public function setUp(): void
	{
		parent::setUp();
		
		$this->user = factory(User::class)->state('with-member-company')->create();
		factory(Team::class, 3)->state('with-company')->make();

		$this->actingAs($this->user);
	}

	public function testIfAuthenticatedMemberCanCreateANewTeam()
	{
		$workspace = factory(Team::class)->state('with-company')->make();
		$data = $workspace->toArray();

		$response = $this->post('teams', $data);

		$this->assertEquals(1, Team::all()->count());
		$this->assertEquals(201, $response->status());
	}

	public function testIfIsGettingOnlyAuthenticatedMemberCompanyTeam()
	{
		factory(Team::class, 1)->create([
			'company_id' => $this->user->member->company_id,
		]);	

		$response = $this->get('teams');

		$this->assertCount(1, json_decode($response->getContent()));
	}
}

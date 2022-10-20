<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Card;
use App\Models\Workspace;
use App\User;
use Tests\TestCase;

class WorkspaceControllerTest extends TestCase
{
	public function testIfAuthenticatedMemberCanCreateANewWorkspace()
	{
		//Given we have an authenticated user
		$this->actingAs(
			factory(User::class)
				->state('with-member-company')
				->create()
		);
		//And a card object
		$workspace = factory(Workspace::class)->state('with-company')->make();

		$data = $workspace->toArray();

		//When user submits post request to create card endpoint
		$result = $this->post('workspaces', $data);

		//It gets stored in the database
		$this->assertEquals(1, Workspace::all()->count());
	}
}

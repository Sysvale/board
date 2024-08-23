<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Workspace;
use App\Models\User;
use Tests\TestCase;

class WorkspaceControllerTest extends TestCase
{
	private $user;

	public function setUp(): void
	{
		parent::setUp();

		$this->user = factory(User::class)->state('with-member-company')->create();
		factory(Workspace::class, 2)->state('with-company')->create();

		$this->actingAs($this->user);
	}

	public function testIfAuthenticatedMemberCanCreateANewWorkspace()
	{
		$this->actingAs($this->user);

		$workspace = factory(Workspace::class)->state('with-company')->make();
		$data = $workspace->toArray();

		$response = $this->post('workspaces', $data);

		$this->assertEquals(1, Workspace::all()->count());
		$this->assertEquals(201, $response->status());
	}

	public function testIfAuthenticatedMemberCanNotAccessAWorkspaceInDifferentCompany()
	{
		$response = $this->get('workspaces');
		$this->assertCount(0, json_decode($response->getContent()));
	}

	public function testIfIsGettingOnlyAuthenticatedMemberWorkspace()
	{
		factory(Workspace::class, 2)->create([
			'company_id' => $this->user->member->company_id,
		]);

		$response = $this->get('workspaces');

		$this->assertCount(2, json_decode($response->getContent()));
	}
}

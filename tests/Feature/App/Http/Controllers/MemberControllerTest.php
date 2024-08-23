<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Tests\TestCase;

class MemberControllerTest extends TestCase
{
	public function setUp(): void
	{
		parent::setUp();

		$this->user = factory(User::class)->state('with-member-company')->create();
		$this->actingAs($this->user);
	}

	public function testIfAuthenticatedMemberCanCreateANewMember()
	{
		$member = factory(Member::class)->state('with-company')->make();
		$data = $member->toArray();

		$data = array_add($data, 'email', 'teste@email.com');
		$data = array_add($data, 'team_ids', [1]);

		$response = $this->post('members', $data);

		$this->assertEquals(2, Member::all()->count());
		$this->assertEquals(201, $response->status());
	}
}

<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Card;
use App\Models\Member;
use App\User;
use Tests\TestCase;

class MemberControllerTest extends TestCase
{
	public function testIfAuthenticatedMemberCanCreateANewMember()
	{
		//Given we have an authenticated user
		$this->actingAs(
			factory(User::class)
				->state('with-member-company')
				->create()
		);
		//And a card object
		$member = factory(Member::class)->state('with-company')->make();

		$data = $member->toArray();

		$data = array_add($data, 'email', 'teste@email.com');
		$data = array_add($data, 'team_ids', [1]);

		//When user submits post request to create card endpoint
		$result = $this->post('members', $data);
		//It gets stored in the database
		$this->assertEquals(2, Member::all()->count());
	}
}

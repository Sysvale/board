<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
	public function index()
	{
		return Member::orderBy('name', 'ASC')->get();
	}

	public function store(Request $in)
	{
		$member = Member::create([
			'name' => $in->name,
			'team_id' => $in->team_id,
			'avatar_url' => $in->avatar_url,
		]);

		return $member;
	}

	public function update(Request $in, $id)
	{
		$params = [
			'name' => $in->name,
			'team_id' => $in->team_id,
			'avatar_url' => $in->avatar_url,
		];

		$member = Member::where('_id', $id);
		$member->update($params);

		return $member->get()->first();
	}

	public function destroy($id)
	{
		$member = Member::where('_id', $id)
			->delete();
		return $member;
	}
}

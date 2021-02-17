<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use App\Http\Resources\MemberResource;

class MemberController extends Controller
{
	public function index()
	{
		return MemberResource::collection(Member::orderBy('name', 'ASC')->get());
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'name' => 'required',
			'team_ids' => 'required|array',
			'avatar_url' => 'nullable|string',
		]);

		$member = Member::create($data);

		$this->syncTeams($member, $data['team_ids']);

		return new MemberResource($member);
	}

	public function update(Request $request, Member $member)
	{
		$data = $request->validate([
			'name' => 'required',
			'team_ids' => 'required|array',
			'avatar_url' => 'nullable|string',
		]);

		$member->update($data);
		$this->syncTeams($member, $data['team_ids']);

		return new MemberResource($member);
	}

	public function destroy($id)
	{
		$member = Member::where('_id', $id)
			->delete();

		return $member;
	}

	private function syncTeams(Member $member, array $team_ids): void
	{
		$current = $member->team_ids;

		$to_remove = array_diff($current, $team_ids);
		$to_add = array_diff($team_ids, $current);

		if (!empty($to_remove)) {
			TeamMember::whereIn('team_id', $to_remove)
				->where('member_id', $member->id)
				->delete();
		}

		foreach ($to_add as $team_id) {
			TeamMember::create([
				'team_id' => $team_id,
				'member_id' => $member->id,
			]);
		}
	}
}

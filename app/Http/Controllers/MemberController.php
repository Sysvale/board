<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Member;
use App\Models\TeamMember;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\MemberResource;
use Illuminate\Support\Facades\Response;
use App\Notifications\WelcomeNotification as WelcomeNotification;

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
			'email' => 'required|email',
			// 'company_id' => 'required',
		]);

		// $company_id = $request->company_id;

		$member = Member::create($data);

		// $member->company()->attach($company_id);

		$isRegisteredUser = User::where('email', $request->email)->exists();

		$this->syncTeams($member, $data['team_ids']);

		if (isset($request->email) && !$isRegisteredUser) {
			$generatedPassword = Str::random(12);
			$user = User::create([
				'name' => $request->name,
				'email' => $request->email,
				'member_id' => $member->id,
				'password' => Hash::make($generatedPassword),
			]);

			// $user->company()->attach($company_id);
		}

		return new MemberResource($member);
	}

	public function update(Request $request, Member $member)
	{
		$data = $request->validate([
			'name' => 'required',
			'team_ids' => 'required|array',
			'avatar_url' => 'nullable|string',
			'email' => 'nullable|string',
			// 'company_id' => 'required',
		]);

		// $company_id = $request->company_id;

		$member->update($data);

		// $this->syncTeams($member, $data['team_ids'], $company_id);

		if ($member->user) {
			$member->user->update([
				'email' => $data['email'],
			]);
		}

		return new MemberResource($member);
	}

	public function destroy(Member $member)
	{
		if ($member->user) {
			$member->user->delete();
		}

		$member->delete();

		return Response::json('Membro excluído com sucesso!');
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
				// 'company_id' => $company_id,
			]);
		}
	}
}

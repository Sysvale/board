<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\WelcomeNotification as WelcomeNotification;

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

		$isRegisteredUser = User::where('email', $in->email)->exists();
		
		if (isset($in->email) && !$isRegisteredUser) {
			$generatedPassword = Str::random(12);

			$user = User::create([
				'name' => $in->name,
				'email' => $in->email,
				'password' => Hash::make($generatedPassword),
			]);
		}

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

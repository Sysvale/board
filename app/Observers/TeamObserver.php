<?php

namespace App\Observers;

use App\Models\Team;
use App\Models\BoardList;
use App\Models\Goal;
use App\Constants\CardTypes;
use Illuminate\Support\Facades\Auth;

class TeamObserver
{
	/**
	 * Handle the team "created" event.
	 *
	 * @param  \App\Models\Team  $team
	 * @return void
	 */
	public function created(Team $team)
	{
		$team->key = $team->id;
		$team->save();

		BoardList::create([
			'name' => 'Sprint - '. $team->name,
			'key' => $team->id,
			'user_story_holder' => true,
			'accepts_card_type' => CardTypes::USER_STORY,
			'is_goalable' => true,
			'position' => 3,
		]);

		Goal::create([
			'title' => 'Defina um objetivo',
			'team_id' => $team->id,
		]);
	}
}

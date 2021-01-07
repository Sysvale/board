<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
	public function getEventsByTeam(Team $team)
	{
		$impediments = Event::where('team_id', $team->id)
			->get()
			->sortByDesc('created_at')
			->values();

		return $impediments;
	}

	public function store(Request $in)
	{
		$event = Event::create([
			'name' => $in->name,
			'members' => $in->members,
			'team_id' => $in->team_id,
			'labels' => $in->labels,
			'start' => $in->start,
			'end' => $in->end,
		]);

		return $event;
	}

	public function update(Request $in, $id)
	{
		$params = [
			'name' => $in->name,
			'labels' => $in->labels,
			'members' => $in->members,
			'team_id' => $in->team_id ?? $in->team['id'],
			'start' => $in->start,
			'end' => $in->end,
		];

		$event = Event::where('_id', $id);
		$event->update($params);

		return $event->get()->first();
	}

	public function destroy($id)
	{
		$event = Event::where('_id', $id)
			->delete();
		return $event;
	}
}

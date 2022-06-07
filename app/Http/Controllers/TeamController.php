<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Http\Resources\TeamResource;
use Illuminate\Http\Request;

class TeamController extends Controller
{
	public function index()
	{
		return TeamResource::collection(Team::get());
	}

	public function update(Request $request, Team $team)
	{
		$data = $request->validate([
			'name' => 'required|string',
			'board_lists' => 'required|array',
		]);

		$team->update($data);
		$team->syncBoardLists($data['board_lists'] ?? []);
		// atualizar board list sprint

		return new TeamResource($team);
	}
}

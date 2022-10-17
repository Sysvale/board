<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\BoardList;
use App\Http\Resources\TeamResource;
use Illuminate\Http\Request;
use App\Constants\CardTypes;
use Illuminate\Support\Facades\Response;


class TeamController extends Controller
{
	public function index()
	{
		return TeamResource::collection(
			Team::get()->load(['board_lists'])
		);
	}

	public function store(Request $request)
	{
		$request->validate([
			'name' => 'required|string',
			'board_lists' => 'required|array',
			'board_lists.*.name' => 'required',
			'board_lists.*.position' => 'required',
			// 'company_id' => 'required',
		]);

		// $company_id = $request->company_id;

		$team = Team::create([
			'name' => $request->name,
		]);

		// $team->company()->attach($company_id);

		$team->syncBoardLists($request['board_lists'] ?? []);

		return new TeamResource($team);
	}

	public function update(Request $request, Team $team)
	{
		$data = $request->validate([
			'name' => 'required|string',
			'board_lists' => 'required|array',
		]);

		$team->update($data);
		$team->syncBoardLists($data['board_lists'] ?? []);

		$this->updateSprintBoardName($team);

		return new TeamResource($team);
	}

	public function destroy(Team $team)
	{
		$this->removeBoardLists($team->board_lists, $team);
		$team->delete();

		return Response::json('Deletado com sucesso.', 200);
	}

	private function removeBoardLists($board_lists, $team)
	{
		$boards = BoardList::where('_id', $board_lists->pluck('id'))->get() ?? [];
		$boards = $boards->merge(BoardList::where('key', $team->id)->get() ?? []);

		$boards->each(function($board) {
			$board->delete();
		});
	}

	private function updateSprintBoardName($team) {
		$sprint_board_list = BoardList::where('key', $team->id)
			->first();
		
		$sprint_board_list->name = 'Sprint - ' . $team->name;

		$sprint_board_list->save();
	}
}

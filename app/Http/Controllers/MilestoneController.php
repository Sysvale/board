<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\Card;
use App\Models\Team;
use App\Models\Workspace;
use App\Models\BoardList;
use App\Http\Resources\MilestoneResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Constants\CardTypes;
use App\Constants\UserStoryStatuses;
use App\Constants\BoardListsKeys;


class MilestoneController extends Controller
{
	public function index()
	{
		return MilestoneResource::collection(
			Milestone::get()
		);
	}

	public function show(Milestone $milestone)
	{
		return new MilestoneResource($milestone);
	}

	public function store(Request $request)
	{
		$request->validate([
			'title' => 'required|string',
			'description' => 'required|string',
			'start_date' => 'required|string',
			'end_date' => 'required|string',
			'team_ids' => 'nullable|array',
			'acceptance_criteria' => 'nullable|array',
		]);

		$milestone = Milestone::create([
			'title' => $request->title,
			'description' => $request->description,
			'start_date' => $request->start_date,
			'end_date' => $request->end_date,
			'acceptance_criteria' => $request->acceptance_criteria,
			'team_ids' => $request->team_ids,
		]);

		return new MilestoneResource($milestone);
	}

	public function update(Request $request, Milestone $milestone)
	{
		$data = $request->validate([
			'title' => 'required|string',
			'description' => 'required|string',
			'start_date' => 'required|string',
			'end_date' => 'required|string',
			'team_ids' => 'nullable|array',
			'acceptance_criteria' => 'nullable|array',
		]);

		$milestone->update($data);

		return new MilestoneResource($milestone);
	}

	public function destroy(Milestone $milestone)
	{
		$milestone->delete();

		return Response::json('Deletado com sucesso.', 200);
	}

	public function getNotStarted(Milestone $milestone)
	{
		$workspaces = Workspace::get();
		
		$backlog_list_keys = BoardList::whereIn('key', $workspaces->map(
			function ($workspace) {
				return BoardListsKeys::BACKLOG.'-'. $workspace->id;
			})
		)
		->get()
		->pluck('key')
		->toArray();

		$board_lists_ids = BoardList::whereIn('key', $backlog_list_keys)
			->orderBy('position')
			->get()
			->pluck('id')
			->toArray();
		

		$cards = Card::where('type', CardTypes::USER_STORY)
			->whereNotNull('workspace_id')
			->where('milestone_id', $milestone->id)
			->whereIn('board_list_id', $board_lists_ids)
			->with('boardList')
			->orderBy('position')
			->get();

		$workspaces_keyed_by_id = $workspaces->keyBy('id');

		return $cards->map(function($item) use ($workspaces_keyed_by_id) {
			$new_item = array();
			$new_item['workspace_name'] = $workspaces_keyed_by_id[explode('-', $item->boardList->key)[1]]->name;
			$new_item['id'] = $item->id;
			$new_item['title'] = $item->title;
			return $new_item;
		});
	}

	public function getOnGoing(Milestone $milestone)
	{
		$teams = Team::get();
		$team_keys = $teams->map(function ($team) { return $team->key; });
		$sprint_list_ids = BoardList::whereIn('key', $team_keys)
			->get()
			->pluck('id')
			->toArray();

		$cards = Card::whereIn('board_list_id', $sprint_list_ids)
			->where('type', CardTypes::USER_STORY)
			->where('milestone_id', $milestone->id)
			->with('boardList')
			->orderBy('position')
			->get();

		$teams_keyed_by_id = $teams->keyBy('id');

		return $cards->map(function($item) use ($teams_keyed_by_id) {
			$new_item = array();
			$new_item['team_name'] = $teams_keyed_by_id[$item->boardList->key]->name;
			$new_item['id'] = $item->id;
			$new_item['title'] = $item->title;
			return $new_item;
		});
	}

	public function getFinished(Milestone $milestone)
	{
		$cards = Card::withTrashed()
			->where('milestone_id', $milestone->id)
			->where('type', CardTypes::USER_STORY)
			->where('status', UserStoryStatuses::DONE)
			->with('boardList')
			->get();

		$teams_keyed_by_id = Team::get()->keyBy('id');

		return $cards->map(function($item) use ($teams_keyed_by_id) {
			$new_item = array();
			$new_item['team_name'] = $teams_keyed_by_id[$item->boardList->key]->name;
			$new_item['id'] = $item->id;
			$new_item['title'] = $item->title;
			return $new_item;
		});
	}
}

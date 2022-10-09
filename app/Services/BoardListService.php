<?php

namespace App\Services;

use App\Constants\BoardListsKeys;
use App\Constants\TeamKeys;
use App\Models\BoardList;
use App\Models\Team;
use App\Models\Workspace;
use Illuminate\Http\Request;
use App\Services\BoardListService;
use App\Services\WorkspaceService;
use App\Constants\CardTypes;

class BoardListService
{
	public function getTaskLists($team_id)
	{
		return BoardList::where('team_id', $team_id)
			->where('is_devlog', '<>', true)
			->where('is_goalable', '<>', true)
			->get();
	}

	public function getDevLists($team_id)
	{
		return BoardList::where('team_id', $team_id)
			->where('is_goalable', '<>', false)
			->get();
	}

	public function getCompanyPlanningLists()
	{
		$workspace_service = new WorkspaceService();

		$planningLists = [
			BoardListsKeys::NOT_PRIORITIZED,
			BoardListsKeys::BACKLOG,
		];

		$workspaces = $workspace_service->getActiveWorkspaces();

		$planningLists = array_merge(
			$planningLists,
			$workspaces->map(
				function ($item) {
					return 'backlog-' . $item->id;
				}
			)->toArray()
		);

		$board_lists = BoardList::whereIn('key', $planningLists)
			->orderBy('position')
			->orderBy('name')
			->get();

		return $board_lists->map(function($item) {
			if($item->key !== BoardListsKeys::NOT_PRIORITIZED
				&& $item->key !== BoardListsKeys::BACKLOG) {
				$item->collapsed = true;
				$item->name = explode(" - ", $item->name)[1] ?? $item->name;
			}
			return $item;
		});
	}

	public function getPlanningLists($workspace_id)
	{
		$planningLists = [
			BoardListsKeys::NOT_PRIORITIZED.'-'.$workspace_id,
			BoardListsKeys::BACKLOG.'-'.$workspace_id,
		];

		$teams = Team::where('workspace_id', $workspace_id)->get();
		$planningLists = array_merge(
			$planningLists,
			$teams->map(
				function ($item) {
					return $item->key;
				}
			)->toArray()
		);

		return BoardList::whereIn('key', $planningLists)
			->orderBy('position')
			->get();
	}

	public function createMany($board_lists, $team_id)
	{
		if(empty($board_lists)) return;

		foreach ($board_lists as $list) {
			$list['team_id'] = $team_id;

			if(empty($list['accepts_card_type'])) {
				$list['accepts_card_type'] = CardTypes::TASK;
			}

			BoardList::create($list);
		}
	}

	public function deleteMany($board_lists_ids)
	{
		if(empty($board_lists_ids)) return;

		foreach ($board_lists_ids as $id) {
			$find = BoardList::find($id);
			if($find) {
				$find->delete();
			}
		}
	}

	public function updateMany($board_lists)
	{
		if(empty($board_lists)) return;

		foreach ($board_lists as $list) {
			$find = BoardList::find($list['id']);
			if($find) {
				$find->fill($list);
				$find->save();
			}
		}
	}
}

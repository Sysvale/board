<?php

namespace App\Http\Controllers;

use App\Constants\BoardListsKeys;
use App\Constants\TeamKeys;
use App\Models\BoardList;
use App\Models\Team;
use App\Models\Workspace;
use Illuminate\Http\Request;
use App\Services\BoardListService;

class BoardListController extends Controller
{
	public function getDefaultLists(Request $in)
	{
		return (new BoardListService())->getDefaultLists($in->team_id);
	}

	public function getDevlogLists(Request $in)
	{
		$default_lists = [Team::where('_id', $in->team_id)->first()->key . 'Dev'];

		$default_lists = array_merge(
			$default_lists,
			(new BoardListService())->getDefaultTaskLists($in->team_id)
		);

		return BoardList::whereIn('key', $default_lists)
			->get()
			->sortBy('position')
			->values();
	}

	public function getPlanningLists(Workspace $workspace)
	{
		$planningLists = [
			BoardListsKeys::NOT_PRIORITIZED.'-'.$workspace->id,
			BoardListsKeys::BACKLOG.'-'.$workspace->id,
		];

		$teams = Team::where('workspace_id', $workspace->id)->get();
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

	public function getCompanyPlanningLists()
	{
		$planningLists = [
			BoardListsKeys::NOT_PRIORITIZED,
			BoardListsKeys::BACKLOG,
		];

		$workspaces = Workspace::get();

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
			}
			return $item;
		});
	}

	public function getIssuesLists()
	{
		$issuesLists = BoardListsKeys::DEFAULT_ISSUES_LISTS;

		$teams = Team::get();
		$issuesLists = array_merge(
			$issuesLists,
			$teams->map(
				function ($item) {
					return $item->key . 'Dev';
				}
			)->toArray()
		);

		return BoardList::whereIn('key', $issuesLists)
			->get()
			->sortBy('position')
			->values();
	}
}

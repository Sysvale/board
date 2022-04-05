<?php

namespace App\Services;

use App\Constants\BoardListsKeys;
use App\Constants\TeamKeys;
use App\Models\BoardList;
use App\Models\Team;
use App\Models\Workspace;
use Illuminate\Http\Request;
use App\Services\BoardListService;

class BoardListService
{
	public function getDefaultLists($team_id)
	{
		return BoardList::whereIn('key', $this->getDefaultTaskLists($team_id))
			->get()
			->sortBy('position')
			->values();
	}

	public function getDefaultTaskLists($team_id)
	{
		$default_lists = BoardListsKeys::DEFAULT_LISTS;

		if ($team_id) {
			$team = Team::where('_id', $team_id)
				->first();

			if ($team->key === TeamKeys::DATA_TEAM) {
				return BoardListsKeys::DT_LISTS;
			}

			if ($team->short_task_flow) {
				return BoardListsKeys::SHORTED_LISTS;
			}

			if ($team->extended_task_flow) {
				return array_merge($default_lists, BoardListsKeys::EXTENDED_LISTS);
			}
		}

		return $default_lists;
	}
}

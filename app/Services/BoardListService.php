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
	public function getTaskLists($team_id)
	{
		return BoardList::where('team_id', $team_id)
			->where('is_devlog', '<>', true)
			->get();
	}

	public function getDevLists($team_id)
	{
		return BoardList::where('team_id', $team_id)
			->get();
	}

	public function createMany($board_lists, $team_id)
	{
		if(empty($board_lists)) return;

		foreach ($board_lists as $list) {
			$list['team_id'] = $team_id;
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

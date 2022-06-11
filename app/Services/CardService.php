<?php

namespace App\Services;

use App\Models\BoardList;
use App\Models\Team;
use App\Models\Card;
use App\Models\Workspace;
use Illuminate\Http\Request;
use App\Services\BoardListService;
use App\Constants\CardTypes;
use App\Http\Resources\CardResource;

class CardService
{
	public function getUserStoriesByTeam($team_key)
	{
		$sprintListId = BoardList::where('key', $team_key)
			->first()->id;

		$cards = Card::where('board_list_id', $sprintListId)
			->where('type', CardTypes::USER_STORY)
			->orderBy('position')
			->get();

		return CardResource::collection($cards);
	}

	public function getTasksFromUserStory($user_story_id, $team_board_lists)
	{
		$cards = Card::whereIn('board_list_id', $team_board_lists)
			->where('user_story_id', $user_story_id)
			->orderBy('position')
			->get();

		return CardResource::collection($cards)->groupBy('board_list_id');
	}

	public function getTasksFromBoard($board_id, $team_board_lists)
	{
		$cards = Card::whereIn('board_list_id', $team_board_lists)
			->where('board_id', $board_id)
			->orderBy('position')
			->get();

		return CardResource::collection($cards)->groupBy('board_list_id');
	}

	public function getCardsByBoardListsIds($team_board_lists)
	{
		$cards = Card::whereIn('board_list_id', $team_board_lists)
			->orderBy('position')
			->get();

		return CardResource::collection($cards)->groupBy('board_list_id');
	}
}

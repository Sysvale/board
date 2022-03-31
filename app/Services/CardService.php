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
}

<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Team;
use App\Models\Event;
use App\Models\Label;
use App\Models\Board;
use App\Models\BoardList;
use App\Constants\CardTypes;
use App\Constants\LabelKeys;
use Illuminate\Http\Request;
use App\Constants\BoardListsKeys;
use App\Constants\TeamKeys;
use App\Constants\BoardKeys;
use App\Http\Resources\CardResource;
use App\Http\Requests\StoreCardRequest;
use App\Services\CardService;
use App\Services\BoardListService;

class CardController extends Controller
{
	private $card_service;
	private $board_list_service;

	public function __construct()
	{
		$this->card_service = new CardService();
		$this->board_list_service = new BoardListService();
	}
	
	public function getTaskCardsFromUserStory(Request $request)
	{
		$request->validate([
			'user_story_id' => 'required|string',
			'team_id' => 'required|string',
		]);
		
		$team_board_lists = $this->board_list_service
			->getTaskLists($request->team_id)
			->pluck('id');

		$cards = $this->card_service->getTasksFromUserStory(
			$request->user_story_id,
			$team_board_lists
		);
		return $this->mountPayload($team_board_lists, $cards);
	}

	public function getTaskCardsFromDevlog(Request $request)
	{
		$request->validate([
			'team_id' => 'required|string',
		]);

		$devlog_board = Board::where('key', BoardKeys::SPRINT_DEVLOG)->first();

		$team_board_lists = (
			$this->board_list_service->getDevLists($request->team_id)
		)->pluck('id');
	
		$cards = $this->card_service->getTasksFromBoard(
			$devlog_board->id,
			$team_board_lists
		);

		return $this->mountPayload($team_board_lists, $cards);
	}

	public function getTaskCardsFromNotPlanned(Request $request)
	{
		$request->validate([
			'team_id' => 'required|string',
		]);

		$not_planned = Board::where('key', BoardKeys::NOT_PLANNED)->first();

		$team_board_lists = (
			$this->board_list_service->getTaskLists($request->team_id)
		)->pluck('id');
	
		$cards = $this->card_service->getTasksFromBoard(
			$not_planned->id,
			$team_board_lists
		);

		return $this->mountPayload($team_board_lists, $cards);
	}

	public function getTaskCardsFromKaizen(Request $request)
	{
		$request->validate([
			'team_id' => 'required|string',
		]);

		$kaizen = Board::where('key', BoardKeys::KAIZEN)->first();

		$team_board_lists = (
			$this->board_list_service->getTaskLists($request->team_id)
		)->pluck('id');
	
		$cards = $this->card_service->getTasksFromBoard(
			$kaizen->id,
			$team_board_lists
		);

		return $this->mountPayload($team_board_lists, $cards);
	}

	public function getCompanyPlanningCards()
	{

		$company_planning_board_lists = (
			$this->board_list_service->getCompanyPlanningLists()
		)->pluck('id');
	
		$cards = $this->card_service->getCardsByBoardListsIds($company_planning_board_lists);

		return $this->mountPayload($company_planning_board_lists, $cards);
	}

	public function getPlanningCards(Request $request)
	{
		$request->validate([
			'workspace_id' => 'required|string',
		]);

		$planning_board_lists = (
			$this->board_list_service->getPlanningLists($request->workspace_id)
		)->pluck('id');
	
		$cards = $this->card_service->getCardsByBoardListsIds($planning_board_lists);

		return $this->mountPayload($planning_board_lists, $cards);
	}

	public function getUserStoriesByTeam(Team $team)
	{
		return $this->card_service->getUserStoriesByTeam($team->key);
	}

	public function getCurrentSprintSummaryByTeam(Team $team)
	{
		return [
			'impediments_amount' => Event::where('team_id', $team->id)->count(),
			'estimated_amount' =>  Card::where(
				'board_list_id',
				BoardList::where('key', $team->key)->first()->id // TODO: Associar team_id quando entrar no board do time (pode ser um observer)
			)->get()->sum('estimated')
		];
	}

	public function store(StoreCardRequest $request)
	{
		$card = Card::create($request->validated());

		$card->company()->associate(
			auth()->user()->member->company_id
		);

		$card->save();

		if ($request->team_key) {
			$card->first_default_board_list_id = $this->getFirstDefaultBoardListId($request->team_key);
		}

		return new CardResource($card);
	}

	public function update(Request $request, Card $card)
	{
		$params = [
			'title' => $request->title,
			'link' => $request->link,
			'labels' => $request->labels,
			'members' => $request->members,
			'estimated' => $request->estimated,
			'team_id' => $request->team_id ?? $request->team['id'] ?? null,
			'acceptance_criteria' => $request->acceptance_criteria,
			'artifacts' => $request->artifacts,
			'checklist' => $request->checklist,
			'board_id' => $request->board_id ?? $request->board['id'] ?? null,
			'workspace_id' => $request->workspace_id,
			'type' => $request->type,
			'bimester_goal' => $request->bimester_goal,
			'is_recurrent' => $request->is_recurrent,
			'is_technical_work' => $request->is_technical_work,
			'rating' => $request->rating,
			'description' => $request->description,
			'status' => $request->status,
		];

		$cleaned_data = array_filter($params, function ($value) {
			return !is_null($value) && $value !== '';
		});

		$card->fill($cleaned_data);
		$card->save();

		return new CardResource($card);
	}

	public function updateCardsPositions(Request $request)
	{
		$request->validate([
			'cards' => 'nullable|array',
			'cards.*.position' => 'required',
			'cards.*.board_list_id' => 'required',
			'cards.*.type' => 'required',
		]);

		$cards = [];
		foreach ($request->cards as $request_card) {
			$card = Card::where('_id', $request_card['id'])->first();
			$card->position = $request_card['position'];
			$card->board_list_id = $request_card['board_list_id'] ?? $card->board_list_id;
			$card->type = $request_card['type'];

			$card->save();

			$cards[] = $card;
		}

		return CardResource::collection($cards);
	}

	public function destroy(Card $card)
	{
		$card->delete();

		return new CardResource($card);
	}

	public function destroyMany(Request $request)
	{
		$request->validate([
			'ids' => 'required|array|min:1',
			'ids.*' => 'required|exists:cards,_id',
		]);

		Card::destroy($request->ids);

		return response()->json(null, 204);
	}

	private function getFirstDefaultBoardListId($team_key)
	{
		$output = BoardListsKeys::DEFAULT_LISTS;
		if ($team_key) {
			$team = Team::where('key', $team_key)
			->first();
			
			if ($team->key === TeamKeys::DATA_TEAM) {
				$output = BoardListsKeys::DT_LISTS;
			}
			
			if ($team->short_task_flow) {
				$output = BoardListsKeys::SHORTED_LISTS;
			}
		}
		return BoardList::where('key', $output[0])->get()->first()->id;
	}

	private function mountPayload($team_board_lists, $cards)
	{
		$payload = array();


		$team_board_lists->each(function ($item) use (&$payload, $cards) {
			$payload[$item] = $cards[$item] ?? [];
		});

		return $payload;
	}
}

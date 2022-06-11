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
use App\Utils\GitlabHandler;
use Illuminate\Http\Request;
use App\Constants\BoardListsKeys;
use App\Constants\GitlabLabelKeys;
use App\Constants\TeamKeys;
use App\Constants\BoardKeys;
use App\Http\Resources\CardResource;
use App\Http\Requests\StoreCardRequest;
use App\Services\CardService;
use App\Services\BoardListService;

class CardController extends Controller
{
	protected $handler;
	private $card_service;
	private $board_list_service;

	public function __construct(GitlabHandler $handler)
	{
		$this->handler = $handler;
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
			'team_id' => $request->team_id ?? $request->team['id'],
			'acceptance_criteria' => $request->acceptance_criteria,
			'artifacts' => $request->artifacts,
			'checklist' => $request->checklist,
			'board_id' => $request->board_id ?? $request->board['id'],
			'workspace_id' => $request->workspace_id,
			'type' => $request->type,
			'has_metric' => $request->has_metric,
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

	public function synchronize()
	{
		$issues = $this->handler->getAllIssues();

		$issues_to_create = $issues->whereNotIn(
			GitlabHandler::ID_FIELD,
			Card::fromGitlab()->pluck('gitlab_id')
		);

		$cards = $this->mapIssuesToCards($issues_to_create);

		if (count($cards)) {
			Card::insert($cards);
		}
	}

	private function mapIssuesToCards($issues)
	{
		return $issues->map(function ($issue) {
			$labels = collect($issue[GitlabHandler::LABELS_FIELD]);

			$card = [];
			$card['title'] = $issue[GitlabHandler::TITLE_FIELD];
			$card['board_list_id'] = $this->getBoardListId($labels);
			$card['labels'] = $this->getLabelIds($labels);
			$card['link'] = $issue[GitlabHandler::LINK_FIELD];
			$card['from_gitlab'] = true;
			$card['gitlab_id'] = $issue[GitlabHandler::ID_FIELD];

			return $card;
		})->values()->toArray();
	}

	private function getBoardListId($labels)
	{
		$key = BoardListsKeys::DEVTASK;
		if ($labels->contains(GitlabLabelKeys::HELP_DESK)) {
			$key = BoardListsKeys::HELPDESK;
		} elseif ($labels->contains(GitlabLabelKeys::BUG)) {
			$key = BoardListsKeys::BUGS;
		}

		return BoardList::where('key', $key)->first()->id;
	}

	private function getLabelIds($labels)
	{
		$dict = [
			GitlabLabelKeys::BACKEND => LabelKeys::BACKEND,
			GitlabLabelKeys::FRONTEND => LabelKeys::FRONTEND,
			GitlabLabelKeys::MOCKUP => LabelKeys::MOCKUP,
			GitlabLabelKeys::BUG => LabelKeys::BUG,
			GitlabLabelKeys::HELP_DESK => LabelKeys::HELP_DESK,
			GitlabLabelKeys::APP => LabelKeys::APP,
			GitlabLabelKeys::UX => LabelKeys::UX,
			GitlabLabelKeys::EXPORT => LabelKeys::EXPORT
		];

		$labels_keys = $labels->map(function ($label) use ($dict) {
			return $dict[$label] ?? null;
		})->filter();

		return Label::whereIn('key', $labels_keys)->pluck('_id')->toArray();
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

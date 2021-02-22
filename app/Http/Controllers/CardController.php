<?php

namespace App\Http\Controllers;

use App\Constants\BoardListsKeys;
use App\Constants\GitlabLabelKeys;
use App\Constants\LabelKeys;
use App\Models\BoardList;
use App\Models\Card;
use App\Models\Label;
use App\Models\Team;
use App\Models\Event;
use App\Utils\GitlabHandler;
use Illuminate\Http\Request;

class CardController extends Controller
{
	protected $handler;

	public function __construct(GitlabHandler $handler)
	{
		$this->handler = $handler;
	}

	public function getCardsByListsIds(Request $in)
	{
		$query = Card::whereIn('board_list_id', $in->lists_ids);

		if ($in->user_story_id) {
			$query = $query->where('user_story_id', $in->user_story_id);
		}

		$cards = $query->get();

		$board_lists = BoardList::get()->keyBy('id');

		$payload = array();
		collect($in->lists_ids)->each(
			function ($item) use (&$payload, &$cards, $board_lists, $in) {
				$sub_query = $cards->where('board_list_id', $item);
				
				// se a lista n for sprint devlog n deve fazer o filtro por time nem por board
				if (!preg_match("/([a-zA-Z0-9()]*)(Dev)/", $board_lists[$item]->key)) {
					if ($in->board_id) {
						$sub_query = $sub_query->where('board_id', $in->board_id);
					}
					if ($in->team_id) {
						$sub_query = $sub_query->where('team_id', $in->team_id);
					}
				}

				if ($board_lists[$item]->key == BoardListsKeys::BACKLOG && $in->workspace_id) {
					$sub_query = $sub_query->where('workspace_id', $in->workspace_id);
				}

				$payload[$item] = $sub_query
					->sortBy('position')
					->values();
			}
		);

		return $payload;
	}

	public function getUserStoriesByTeam(Team $team)
	{
		$sprintListId = BoardList::where('key', $team->key)
			->first()->id;

		$cards = Card::where('board_list_id', $sprintListId)
			->where('is_user_story', true)
			->get()
			->sortBy('position')
			->values();

		return $cards;
	}

	public function getCurrentSprintSummaryByTeam(Team $team)
	{
		$cards = Card::where('team_id', $team->id)->get();

		return [
			'impediments_amount' => Event::where('team_id', $team->id)->count(),
			'estimated_amount' =>  Card::where(
				'board_list_id',
				BoardList::where('key', $team->key)->first()->id // TODO: Associar team_id quando entrar no board do time (pode ser um observer)
			)->get()->sum('estimated')
		];
	}

	public function store(Request $in)
	{
		$card = Card::create([
			'board_list_id' => $in->board_list_id,
			'title' => $in->title,
			'user_story_id' => $in->user_story_id,
			'members' => $in->members,
			'team_id' => $in->team_id,
			'board_id' => $in->board_id,
			'labels' => $in->labels,
			'link' => $in->link,
			'workspace_id' => $in->workspace_id,
		]);

		if ($card->boardList && $this->isListAnUserStoryHolder($card->boardList->key)) {
			$card->is_user_story = true;
			$card->save();
		}
		
		unset($card->boardList);

		return $card;
	}

	public function update(Request $in, $id)
	{
		$params = [
			'title' => $in->title,
			'link' => $in->link,
			'labels' => $in->labels,
			'members' => $in->members,
			'estimated' => $in->estimated,
			'team_id' => $in->team_id ?? $in->team['id'],
			'acceptance_criteria' => $in->acceptance_criteria,
			'checklist' => $in->checklist,
			'board_id' => $in->board_id ?? $in->board['id'],
			'workspace_id' => $in->workspace_id,
		];
		
		$query = Card::where('_id', $id);
		$query->update($params);

		$card = $query->get()->first();

		$list_key = BoardList::where('_id', $card->boardList->id)->first()->key;
		
		$card->is_user_story = $this->isListAnUserStoryHolder($list_key);

		$card->save();

		return $card;
	}

	public function updateCardsPositions(Request $in)
	{
		$requestCards = collect($in->cards);

		$result = $requestCards
			->each(
				function ($requestCard) {
					$card = Card::where('_id', $requestCard['id'])->first();
					$card->position = $requestCard['position'];
					$card->board_list_id = $requestCard['board_list_id']
						?? $card->board_list_id;
					$card->save();
				}
			);

		return $result;
	}

	public function destroy($id)
	{
		$card = Card::where('_id', $id)
			->delete();
		return $card;
	}

	private function isListAnUserStoryHolder($key)
	{
		$teams = Team::get()->map(function ($item) {
			return $item['key'];
		});
		$user_story_holders = array_merge(
			$teams->toArray(),
			[
				BoardListsKeys::BACKLOG
			]
		);
		return in_array(
			$key,
			$user_story_holders
		);
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
}

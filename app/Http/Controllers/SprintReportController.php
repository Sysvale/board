<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Label;
use App\Models\Workspace;
use App\Models\SprintReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\WorkspaceResource;
use App\Http\Resources\SprintReportResource;
use App\Models\Card;
use App\Services\CardService;
use App\Services\BoardListService;

class SprintReportController extends Controller
{
	public function store(Request $request)
	{
		$data = $request->validate([
			'team_id' => 'required|string',
			'notes' => 'string|string',
			'members' => 'required|array',
			'impediments_quantity' => 'integer',
			'started_at' => 'required|date',
			'finished_at' => 'required|date',
			'cards.*.id' => 'required',
			'cards.*.status' => 'required',
			'cards.*.title' => 'required',
			'cards.*.estimated' => 'nullable',
			'cards.*.backlog_labels' => 'nullable',
			'cards.*.is_recurrent' => 'nullable',
			'cards.*.bimester_goal' => 'nullable',
		]);

		$sprint_report = SprintReport::create($data);

		if ($sprint_report) {
			(new CardService())->deleteMany(collect($request->cards)
				->filter(function($item) {
					return !!$item['should_be_deleted'];
				})->pluck('id'));
		}

		return new SprintReportResource($sprint_report);
	}

	public function getSprintReportByTeamId(Team $team)
	{
		return SprintReport::where('team_id', $team->id)
			->orderBy('startedAt')
			->get()
			->reverse()
			->values();
	}

	public function getCurrentSprintOverviewByTeam(Team $team)
	{

		$output = [];

		$sprint_backlog_items = (new CardService())->getUserStoriesByTeam($team->key);
		$default_lists = (new BoardListService())
			->getTaskLists($team->id);

		$cards = Card::whereIn('user_story_id', $sprint_backlog_items->pluck('id')->toArray())
			->whereIn('board_list_id', $default_lists->map( function ($item) {
				return $item->id;
			}))
			->get();

		$sprint_backlog_items
			->each( function ($backlog_item) use ($default_lists, $cards, &$output){
				$item = ['name' => $backlog_item->title];
				$default_lists->each( function ($list) use (&$item, $cards, $backlog_item) {
					$count = $cards
						->where('user_story_id', $backlog_item->id)
						->where('board_list_id', $list->id)
						->count();

					$item[$list->name] = $count + ($item[$list->name] ?? 0);
				});

				array_push($output, $item);
			});

		$headers = [
			[
				'text' => 'Descrição',
				'value' => 'name',
				'align' => 'center',
				'sortable' => false,
			]
		];

		$default_lists->each( function ($item) use (&$headers) {
			$item = [
				'text' => $item->name,
				'value' => $item->name,
				'align' => 'center',
				'sortable' => false,
			];
			array_push($headers, $item);
		});


		return [
			'headers' => $headers,
			'data' => $output
		];
	}


}

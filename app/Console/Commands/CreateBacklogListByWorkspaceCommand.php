<?php

namespace App\Console\Commands;

use App\Constants\BoardListsKeys;
use App\Constants\LabelKeys;
use App\Constants\TeamKeys;
use App\Models\BoardList;
use App\Models\Goal;
use App\Models\Card;
use App\Models\Workspace;
use Illuminate\Console\Command;

class CreateBacklogListByWorkspaceCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'create:backlog-list-by-workspace';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Cria uma lista backlog para cara workspace';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$this->info('Iniciando...');

		$workspaces = Workspace::get();
		$backlog_board_list = BoardList::where('key', BoardListsKeys::BACKLOG)->first();
		$not_prioritized_board_list = BoardList::where('key', BoardListsKeys::NOT_PRIORITIZED)->first();

		$workspaces->each(function ($workspace) use ($backlog_board_list, $not_prioritized_board_list) {
			$new_backlog_board_list = $this->createBoardList(BoardListsKeys::BACKLOG, $workspace, 1);
			$this->setupCardsInToNewBoardList($workspace, $backlog_board_list, $new_backlog_board_list);

			$new_not_prioritized_board_list = $this->createBoardList(BoardListsKeys::NOT_PRIORITIZED, $workspace, 0);
			$this->setupCardsInToNewBoardList($workspace, $not_prioritized_board_list, $new_not_prioritized_board_list);
		});

		$this->setupBacklogAndNotPrioritizedPositions();

		$this->info('Finalizado!');
	}

	private function createBoardList($key, $workspace, $position)
	{
		return BoardList::create([
			'name' => $this->getBoardListLabel($key, $workspace),
			'key' => $this->getBoardListKey($key, $workspace),
			'accepts_card_type' => $this->getAcceptsCardType($key),
			'user_story_holder' => true,
			'position' => $position,
		]);
	}

	private function getBoardListLabel($key, $workspace)
	{
		$label = 'Backlog';
		if($key === BoardListsKeys::NOT_PRIORITIZED) {
			$label = 'Não priorizados';
		}

		$label = $label . ' - ' . $workspace->name;

		return $label;
	}

	private function getBoardListKey($key, $workspace)
	{
		return $key.'-'.$workspace->id;
	}

	private function getAcceptsCardType($key)
	{
		$accepts_card_type = 'user-story';
		if($key === BoardListsKeys::NOT_PRIORITIZED) {
			$accepts_card_type = 'not-prioritized';
		}

		return $accepts_card_type;
	}

	private function setupCardsInToNewBoardList($workspace, $old_board_list, $new_board_list)
	{
		$cards = Card::where('board_list_id', $old_board_list->id)
			->where('workspace_id', $workspace->id)
			->get();

		$cards->each(function($card) use ($new_board_list){
			$card->board_list_id = $new_board_list->id;
			$card->save();
		});
	}

	private function setupBacklogAndNotPrioritizedPositions() {
		$backlog_board_list = BoardList::where('key', BoardListsKeys::BACKLOG)->first();
		$not_prioritized_board_list = BoardList::where('key', BoardListsKeys::NOT_PRIORITIZED)->first();

		$backlog_board_list->position = 0;
		$backlog_board_list->save();

		$not_prioritized_board_list->position = -1;
		$not_prioritized_board_list->save();
	}
}

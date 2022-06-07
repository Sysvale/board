<?php

namespace App\Console\Commands;

use App\Constants\BoardListsKeys;
use App\Constants\LabelKeys;
use App\Constants\TeamKeys;
use App\Models\BoardList;
use App\Models\Team;
use App\Models\Card;
use App\Models\Workspace;
use Illuminate\Console\Command;

class CreateListsByTeamCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'create:lists-by-team';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Cria listas para cada time';

	protected $board_lists_to_remove = [];

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

		$teams = Team::get();

		$teams->each(function ($team) {
			if ($team->key === TeamKeys::DATA_TEAM) {
				$this->updateBoardListIdByListKey(BoardListsKeys::DT_LISTS, $team);
			} else if ($team->short_task_flow) {
				$this->updateBoardListIdByListKey(BoardListsKeys::SHORTED_LISTS, $team);
			} else if ($team->extended_task_flow) {
				$this->updateBoardListIdByListKey(array_merge(BoardListsKeys::DEFAULT_LISTS, BoardListsKeys::EXTENDED_LISTS), $team);
			} else {
				$this->updateBoardListIdByListKey(BoardListsKeys::DEFAULT_LISTS, $team);
			}
		});

		
		$this->removeOldLists();

		$this->info('Finalizado!');
	}

	private function updateBoardListIdByListKey($list_keys, $team)
	{
		foreach($list_keys as $list_key) {
			$current_board_list = BoardList::where('key', $list_key)->first();
			$new_board_list = $this->createBoardList($current_board_list, $team);
			$this->setupCardsInToNewBoardList($current_board_list, $new_board_list);
		}
	}

	private function createBoardList($current_board_list, $team)
	{
		$this->addToRemoveList($current_board_list->id);
		$new_board_list = $current_board_list->replicate();
		$new_board_list->team_id = $team->id;
		$new_board_list->save();
		return $new_board_list;
	}

	private function setupCardsInToNewBoardList($current_board_list, $new_board_list)
	{
		$cards = Card::where('board_list_id', $current_board_list->id)
			->get();

		$cards->each(function($card) use ($new_board_list) {
			$card->board_list_id = $new_board_list->id;
			$card->save();
		});
	}

	private function addToRemoveList($id)
	{
		array_push($this->board_lists_to_remove, $id);
	}

	private function removeOldLists()
	{
		$board_lists_to_remove = BoardList::whereIn('id', $this->board_lists_to_remove)->get();
		$board_lists_to_remove->each(function($list) {
			$list->delete();
		});
	}
}
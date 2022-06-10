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

		$this->fixCodeReviewListKey();

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

		$teams->each(function ($team) {
			$this->updateBoardListIdByListKey([$team->key . 'Dev'], $team, ['is_devlog' => true]);
		});

		
		$this->removeOldLists();

		$this->info('Finalizado!');
	}

	private function fixCodeReviewListKey()
	{
		$code_review = BoardList::where('name', 'Code Review')->get();

		$code_review->each(function($item) {
			$item->key = BoardListsKeys::CODE_REVIEW;
			$item->save();
		});
	}

	private function updateBoardListIdByListKey($list_keys, $team, $extra_data = [])
	{
		foreach($list_keys as $list_key) {
			$current_board_list = BoardList::where('key', $list_key)->first();
			if(empty($current_board_list)) continue;
			$new_board_list = $this->createBoardList($current_board_list, $team, $extra_data);
			$this->setupCardsInToNewBoardList($current_board_list, $new_board_list);
		}
	}

	private function createBoardList($current_board_list, $team, $extra_data = [])
	{
		$this->info('-------------' . PHP_EOL);
		$this->info("Replicando lista [" . $current_board_list->name . "] do time [" . $team->name . "]");
		$this->addToRemoveList($current_board_list->id);
		$new_board_list = $current_board_list->replicate();
		$new_board_list->team_id = $team->id;
		$new_board_list->fill($extra_data);
		$new_board_list->save();
		return $new_board_list;
	}
	
	private function setupCardsInToNewBoardList($current_board_list, $new_board_list)
	{
		$cards = Card::where('board_list_id', $current_board_list->id)
			->get();

		$this->info("Movendo [" . $cards->count() . "] cards da lista [" . $current_board_list->name . "] para [" . $new_board_list->name . "]" );

		$bar = $this->output->createProgressBar($cards->count());

		$cards->each(function($card) use ($new_board_list, $bar) {
			$card->board_list_id = $new_board_list->id;
			$card->save();
			$bar->advance();
		});

		$this->info(PHP_EOL);
	}

	private function addToRemoveList($id)
	{
		array_push($this->board_lists_to_remove, $id);
	}

	private function removeOldLists()
	{
		$board_lists_to_remove = BoardList::whereIn('_id', $this->board_lists_to_remove)->get();
		$this->info("Deletando " . $board_lists_to_remove->count() . " listas");
		$board_lists_to_remove->each(function($list) {
			$list->delete();
		});
	}
}
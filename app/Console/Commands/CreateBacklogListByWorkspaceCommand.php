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
		$not_prioritized_board_list = BoardList::where('key', BoardListsKeys::BACKLOG)->first();

		$workspaces->each(function ($workspace) use ($backlog_board_list, $not_prioritized_board_list) {
			$value = BoardList::create([
				'name' => 'Backlog - ' . $workspace->name,
				'key' => BoardListsKeys::BACKLOG . '-' . $workspace->id,
				'accepts_card_type' => 'user-story',
				'position' => 1,
			]);

			$value = BoardList::create([
				'name' => 'Não Priorizados - ' . $workspace->name,
				'key' => BoardListsKeys::NOT_PRIORITIZED . '-' . $workspace->id,
				'accepts_card_type' => 'user-story',
				'position' => 0,
			]);

			$cards = Card::where('board_list_id', $backlog_board_list->id)
				->where('workspace_id', $workspace->id)
				->get();

			$cards = Card::where('board_list_id', $not_prioritized_board_list->id)
				->where('workspace_id', $workspace->id)
				->get();

			$cards->each(function($card) use ($value){
				$card->board_list_id = $value->id;
				$card->save();
			});
		});
	}
}

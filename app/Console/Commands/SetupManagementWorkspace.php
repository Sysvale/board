<?php

namespace App\Console\Commands;

use App\Constants\BoardListsKeys;
use App\Models\BoardList;
use App\Models\Team;
use Illuminate\Console\Command;

class SetupManagementWorkspace extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'setup:management-workspace';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '[Temporário] Configura workspace para o time de gestão';

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
		$this->createSprintBoardLists();
	}

	private function createSprintBoardLists()
	{
		$teams = Team::all();

		$teams->each(function ($team) {
			$board_list = BoardList::where('key', $team->key)->count();
			if ($board_list === 0) {
				BoardList::create([
				'name' => 'Sprint - '. $team->name,
				'key' => $team->key,
				'user_story_holder' => true,
				'position' => 3,
				]);
			}
		});
	}
}

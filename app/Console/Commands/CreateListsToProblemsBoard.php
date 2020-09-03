<?php

namespace App\Console\Commands;

use App\Constants\BoardListsKeys;
use App\Models\BoardList;
use Illuminate\Console\Command;

class CreateListsToProblemsBoard extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'create:lists-to-problem-board';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '[Temporário] Cria listas devlog, design system e segurança e ajusta posições das demais listas...';

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
		$list = BoardList::create([
			'name' => 'Design System',
			'key' => BoardListsKeys::DESIGN_SYSTEM,
			'position' => 3,
		]);

		$list = BoardList::create([
			'name' => 'Sysgurança',
			'key' => BoardListsKeys::SYS_SECURITY,
			'position' => 4,
		]);

		$list = BoardList::create([
			'name' => 'Devlog',
			'key' => BoardListsKeys::DEVLOG,
			'position' => 5,
		]);

		$this->info('Listas criadas');

		$dev_boards = BoardList::where(
			'key',
			'regexp',
			"/([a-zA-Z0-9()]*)(Dev)/"
		)->get();

		$dev_boards->each(function ($item) {
			$this->info($item->name . ' -  old: ' . $item->position);
		});
		
		$dev_boards->each(function ($item) {
			$item->position = $item->position + 3;
			$item->save();
		});

		$dev_boards->each(function ($item) {
			$this->info($item->name . ' -  new: ' . $item->position);
		});
	}
}

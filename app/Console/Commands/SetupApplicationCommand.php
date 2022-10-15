<?php

namespace App\Console\Commands;

use App\User;
use App\Constants\BoardListsKeys;
use App\Constants\BoardKeys;
use App\Constants\CardTypes;
use App\Models\BoardList;
use App\Models\Board;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SetupApplicationCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'setup:app';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Faz as configurações iniciais para funcionamento da aplicação';

	private $is_dev = false;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */

	public function __construct()
	{
		parent::__construct();
		$this->is_dev = config('app.env') === 'local';
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$this->info('Iniciando...');

		if($this->is_dev) {
			$this->createAnUser();
		}

		$this->createBoards();
		$this->createCompanyBacklogBoardLists();


		$this->info('Finalizado!');
	}

	private function createBoards()
	{
		$this->titleDivider('Criando boards/lanes...');

		$default_boards = [
			[
				'name' => 'Impedimentos',
				'key' => BoardKeys::IMPEDIMENTS,
			],
			[
				'name' => 'Não planejados',
				'key' => BoardKeys::NOT_PLANNED,
			],
			[
				'name' => 'Kaizen',
				'key' => BoardKeys::KAIZEN,
			],
			[
				'name' => 'Sprint Backlog',
				'key' => BoardKeys::SPRINT_BACKLOG,
			],
			[
				'name' => 'Sprint Devlog',
				'key' => BoardKeys::SPRINT_DEVLOG,
			],
		];

		collect($default_boards)->each(
			function ($item) {
				$exists = Board::where('key', $item['key'])->count() > 0;
				if (!$exists) {
					$list = new Board();
					$list->name = $item['name'];
					$list->key = $item['key'];
					$list->save();
				} else {
					$this->info('Board ' . $item['name'] . ' já existe.');
				}
			}
		);
	}

	private function createCompanyBacklogBoardLists()
	{
		$this->titleDivider('Criando listas default...');

		$company_board_lists = [
			[
				'name' => 'Não priorizados',
				'key' => BoardListsKeys::NOT_PRIORITIZED,
				'accepts_card_type' => CardTypes::NOT_PRIORITIZED,
				'position' => -1,
			],
			[
				'name' => 'Backlog',
				'key' => BoardListsKeys::BACKLOG,
				'accepts_card_type' => CardTypes::USER_STORY,
				'position' => 0,
			],
		];

		collect($company_board_lists)->each(function ($item) {
			$exists = BoardList::where('key', $item['key'])->count() > 0;
			if (!$exists) {
				$list = new BoardList();
				$list->name = $item['name'];
				$list->key = $item['key'];
				$list->position = $item['position'];
				$list->accepts_card_type = $item['accepts_card_type'];
				$list->save();
			} else {
				$this->info('BoardList ' . $item['name'] . ' já existe.');
			}
		});
	}

	private function createAnUser()
	{
		$this->titleDivider('Criando usuário padrão...');
		if (User::count() === 0) {
			User::create([
				'email' => 'admin@admin.com',
				'password' => Hash::make('admin'),
			]);
		} else {
			$this->info('Já existe um usuário nesta base de dados');
		}
	}

	private function titleDivider($text)
	{
		$this->info('----' . PHP_EOL);
		$this->info($text . PHP_EOL);
	}
}

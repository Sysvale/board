<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Constants\BoardListsKeys;
use App\Constants\BoardKeys;
use App\Constants\CardTypes;
use App\Models\BoardList;
use App\Models\Board;
use App\Models\Member;
use App\Models\Company;
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
			$company = $this->createACompany();
			$member = $this->createAMember($company);
			$this->createAnUser($member);
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
				'name' => 'Agenda',
				'key' => BoardKeys::IMPEDIMENTS,
			],
			[
				'name' => 'Não planejados e impedimentos',
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

	private function createACompany()
	{
		$this->titleDivider('Criando empresa/compania padrão...');

		if(Company::get()->count() === 0) {
			return factory(Company::class)->create();
		}

		$this->info('Empresa/compania padrão já existe');
		return Company::get()->first();

	}

	private function createAMember($company)
	{
		$this->titleDivider('Criando membro padrão...');

		if(Member::get()->count() === 0) {
			$member = Member::create([
				'name' => 'Administrador',
			]);

			$member->company()->associate($company->id);

			$member->save();
			return $member;
		}

		$this->info('Membro padrão já existe');
		return Member::get()->first();
	}

	private function createAnUser($member)
	{
		$this->titleDivider('Criando usuário padrão...');

		if(User::get()->count() === 0) {
			return User::create([
				'email' => 'admin@admin.com',
				'password' => Hash::make('admin'),
				'member_id' => $member->id,
			]);
		}

		$this->info('Usuário padrão já existe');
		return User::get()->first();
	}

	private function titleDivider($text)
	{
		$this->info('----' . PHP_EOL);
		$this->info($text . PHP_EOL);
	}
}

<?php

namespace App\Console\Commands;

use App\Constants\BoardListsKeys;
use App\Constants\LabelKeys;
use App\Constants\TeamKeys;
use App\Models\BoardList;
use App\Models\Goal;
use App\Models\Label;
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
		$this->info('Iniciando...');
		$this->createLabels();
		$this->createTeams();
		$this->createSprintBoardLists();
		$this->createDoingBoardList();
		$this->info('Tudo pronto!');
	}

	private function createLabels()
	{
		$managementLabels = [
			[
				'name' => 'Hotfix',
				'key' => LabelKeys::HOTFIX,
				'color' => '#CC2A48',
				'text_color' => 'white',
			],
			[
				'name' => 'Bugfix',
				'key' => LabelKeys::BUGFIX,
				'color' => '#FFAA2B',
				'text_color' => 'rgba(0, 0, 0, 0.75)',
			],
			[
				'name' => 'Reunião',
				'key' => LabelKeys::MEET,
				'color' => '#815AA5',
				'text_color' => '#fff',
			],
			[
				'name' => 'Feature',
				'key' => LabelKeys::FEATURE,
				'color' => '#028D29',
				'text_color' => '#fff',
			],
			[
				'name' => 'Débito técnico',
				'key' => LabelKeys::TECHINICAL_DEBIT,
				'color' => '#FFAA2B',
				'text_color' => 'rgba(0, 0, 0, 0.75)',
			],
	
			[
				'name' => 'Discovery',
				'key' => LabelKeys::DISCOVERY,
				'color' => '#3171A3',
				'text_color' => '#fff',
			],
		];

		$this->info('Criando labels...');

		
		$value = Label::insert($managementLabels);

		if ($value) {
			$this->info('Labels inseridas com sucesso');
		}
	}

	private function createTeams()
	{
		$teams = [
			[
				'name' => 'DataTeam',
				'key' => TeamKeys::DATA_TEAM,
			],
		];

		$this->info('Criando times...');

		$value = Team::insert($teams);

		if ($value) {
			$this->info('Times inseridos com sucesso');
		}
	}

	private function createDoingBoardList()
	{
		$this->info('Criando doing board list...');

		$value = BoardList::create([
			'name' => 'To do - 20',
			'key' => BoardListsKeys::TODO_DT,
			'position' => 1,
		]);

		$value = BoardList::create([
			'name' => 'Doing (Normal) - 5',
			'key' => BoardListsKeys::DOING_DT_NORMAL,
			'position' => 2,
		]);

		$value = BoardList::create([
			'name' => 'Doing (Meetings) - 2',
			'key' => BoardListsKeys::DOING_DT_MEETINGS,
			'position' => 3,
		]);

		$value = BoardList::create([
			'name' => 'Doing (Hotfix ou bugfix) - 2',
			'key' => BoardListsKeys::DOING_DT_HOT_BUG,
			'position' => 4,
		]);

		$value = BoardList::create([
			'name' => 'Waiting 1 approve - 2',
			'key' => BoardListsKeys::WAITING_ONE_APPROVE,
			'position' => 5,
		]);

		$value = BoardList::create([
			'name' => 'Waiting 2 approves - 2',
			'key' => BoardListsKeys::DOING,
			'position' => 6,
		]);

		if ($value) {
			$this->info('Boards criadas com sucesso');
		}
	}

	private function createSprintBoardLists()
	{
		$teams = Team::all();
		
		$this->info('Criando sprint board lists...');

		$teams->each(function ($team) {
			$board_list = BoardList::where('key', $team->key)->count();
			if ($board_list === 0) {
				$value = BoardList::create([
				'name' => 'Sprint - '. $team->name,
				'key' => $team->key,
				'user_story_holder' => true,
				'position' => 3,
				]);

				if ($value) {
					$this->info('Lista criada com sucesso...');
				}
			}
		});
	}
}

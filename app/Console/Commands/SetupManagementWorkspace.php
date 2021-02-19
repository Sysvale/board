<?php

namespace App\Console\Commands;

use App\Constants\BoardListsKeys;
use App\Constants\LabelKeys;
use App\Constants\TeamKeys;
use App\Models\BoardList;
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
				'name' => 'Gente e Gestão',
				'key' => LabelKeys::PM,
				'color' => '#815AA5',
				'text_color' => '#fff',
			],
			[
				'name' => 'Financeiro',
				'key' => LabelKeys::FINANCIAL,
				'color' => '#028D29',
				'text_color' => '#fff',
			],
			[
				'name' => 'Comunicação',
				'key' => LabelKeys::COMUNICATION,
				'color' => '#FFAA2B',
				'text_color' => 'rgba(0, 0, 0, 0.75)',
			],
	
			[
				'name' => 'Comercial',
				'key' => LabelKeys::SALES,
				'color' => '#3171A3',
				'text_color' => '#fff',
			],
			[
				'name' => 'Projetos',
				'key' => LabelKeys::PROJECTS,
				'color' => '#54EF1A',
				'text_color' => 'rgba(0, 0, 0, 0.75)',
			],
			[
				'name' => 'Reunião',
				'key' => LabelKeys::MEET,
				'color' => '#EC6646',
				'text_color' => '#fff',
			],
			[
				'name' => 'Gestão de cidades',
				'key' => LabelKeys::CITY_MANAGEMENT,
				'color' => '#97D480',
				'text_color' => 'rgba(0, 0, 0, 0.75)',
			],
	
			[
				'name' => 'Produto',
				'key' => LabelKeys::PRODUCT,
				'color' => '#21222E',
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
				'name' => 'SysIN',
				'key' => TeamKeys::SYS_IN,
				'short_task_flow' => true,
			],
			[
				'name' => 'SysOUT',
				'key' => TeamKeys::SYS_OUT,
				'short_task_flow' => true,
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
			'name' => 'Doing',
			'key' => BoardListsKeys::DOING,
			'position' => 1,
		]);

		if ($value) {
			$this->info('Doing board list criado com sucesso');
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

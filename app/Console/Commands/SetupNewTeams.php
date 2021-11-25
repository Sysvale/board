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

class SetupNewTeams extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'setup:new-teams';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Configura entidades de novos times';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */

	private $teams_to_insert = [
		[
			'name' => 'Grasshopper',
			'key' => TeamKeys::GRASSHOPPER,
		]
	];

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
		$this->createDummyGoalsToTeams();
		$this->info('Tudo pronto!');
	}

	private function createLabels()
	{

		$default_labels = [
			LabelKeys::BACKEND,
			LabelKeys::BUG,
			LabelKeys::DOCUMENTATION,
			LabelKeys::FRONTEND,
			LabelKeys::INFRA,
			LabelKeys::MOCKUP,
			LabelKeys::MEET,
			LabelKeys::SECURITY,
			LabelKeys::HELP_DESK,
			LabelKeys::UX,
		];

		$labels_to_duplicate = Label::whereIn('key', $default_labels)->get([
			'name',
			'key',
			'color',
			'text_color',
		])->toArray();

		$this->info('Criando labels...');

		$value = Label::insert($labels_to_duplicate);

		if ($value) {
			$this->info('Labels inseridas com sucesso');
		}
	}

	private function createTeams()
	{
		$this->info('Criando times...');

		$value = Team::insert($this->teams_to_insert);

		if ($value) {
			$this->info('Times inseridos com sucesso');
		}
	}

	private function createDoingBoardList()
	{
		$this->info('Criando doing board list...');

		$value = BoardList::create([
			'name' => 'To do',
			'key' => BoardListsKeys::TODO,
			'position' => 1,
		]);

		$value = BoardList::create([
			'name' => 'Em desenvolvimento',
			'key' => BoardListsKeys::DOING,
			'position' => 2,
		]);

		$value = BoardList::create([
			'name' => 'Code Review',
			'key' => BoardListsKeys::CODE_REVIEW,
			'position' => 3,
		]);

		$value = BoardList::create([
			'name' => 'Done/To Release',
			'key' => BoardListsKeys::DONE,
			'position' => 4,
		]);

		$value = BoardList::create([
			'name' => 'Deploy',
			'key' => BoardListsKeys::DEPLOY,
			'position' => 5,
		]);

		if ($value) {
			$this->info('Listas criadas com sucesso');
		}
	}

	private function createSprintBoardLists()
	{
		$teams = $this->getTeamsByKeys();
		
		$this->info('Criando sprint board lists...');

		$teams->each(function ($team) {
			$board_list_not_exists = BoardList::where('key', $team->key)->count() === 0;
			if ($board_list_not_exists) {
				$value = BoardList::create([
					'name' => 'Sprint - '. $team->name,
					'key' => $team->key,
					'user_story_holder' => true,
					'position' => 3,
				]);

				if ($value) {
					$this->info('Lista de sprint criada com sucesso...');
				}
			}
		});
	}

	private function createDummyGoalsToTeams()
	{
		$this->info('Criando dummy goals...');

		$teams = $this->getTeamsByKeys();

		$teams->each(function ($team) {
			$value = Goal::create([
				'title' => 'Defina um objetivo da sprint',
				'team_id' => $team->id,
			]);

			if($value) {
				$this->info('Dummy goal criado com sucesso para o time ' . $team->name);
			}
		});
	}

	private function getTeamsByKeys()
	{
		return Team::whereIn('key', collect($this->teams_to_insert)
			->map(function ($item) {
				return $item['key'];
			}))->get();
	}
}

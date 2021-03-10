<?php

namespace App\Console\Commands;

use App\Models\Goal;
use App\Models\Team;
use App\Models\Workspace;
use Illuminate\Console\Command;

class CreateDummyGoals extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'create:dummy-goals';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '[Temporário] Cria dummy goals para cada time e workspaces';

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
		$workspaces->each(function ($workspace) {
			Goal::create([
				'title' => 'Defina um objetivo do seu backlog',
				'workspace_id' => $workspace->id,
			]);
		});

		$teams = Team::get();
		$teams->each(function ($team) {
			Goal::create([
				'title' => 'Defina um objetivo da sprint',
				'team_id' => $team->id,
			]);
		});

		$this->info('Fim!');
	}
}

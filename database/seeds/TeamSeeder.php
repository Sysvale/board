<?php

use App\Constants\TeamKeys;
use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\Goal;

class TeamSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$defaultTeams = collect($this->getDefaultTeams());

		$defaultTeams->each(function ($item) {
			$list = new Team();
			$list->name = $item['name'];
			$list->key = $item['key'];
			$list->extended_task_flow = $item['extended_task_flow'] ?? false;
			$list->save();

			Goal::create([
				'title' => 'Defina um objetivo da sprint',
				'team_id' => $list->id,
			]);
		});
	}

	private function getDefaultTeams()
	{
		return [
			[
				'name' => 'The Avengers',
				'key' => TeamKeys::AVENGERS,
				'extended_task_flow' => true,
			],
			[
				'name' => 'Stepper Labs',
				'key' => TeamKeys::STEPPER,
			],
			[
				'name' => 'Breakout One',
				'key' => TeamKeys::BREAKOUT_ONE,
			],
		];
	}
}

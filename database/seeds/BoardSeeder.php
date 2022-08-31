<?php

use Illuminate\Database\Seeder;
use App\Constants\BoardKeys;
use App\Models\Board;

class BoardSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$defaultBoards = collect($this->getDefaultBoards());

		$defaultBoards->each(
			function ($item) {
				$list = new Board();
				$list->name = $item['name'];
				$list->key = $item['key'];
				$list->save();
			}
		);
	}

	private function getDefaultBoards()
	{
		return [
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
	}
}

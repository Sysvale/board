<?php

use App\Dictionary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

abstract class DictionaryBaseSeeder extends Seeder
{
	protected $group = null;

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->importEntries(
			$this->getEntries()
		);
	}

	protected function importEntries($entries)
	{
		foreach ($entries as $entry) {
			if ($this->doesntHave($entry)) {
				$entry['group'] = $this->group;

				$newEntries[] = $entry;

				$name = $entry['name'];
				$group = $entry['group'];

				$this->command->info("Entrada '$name' do tipo '$group' inserida!");
			}
		}

		if (!empty($newEntries)) {
			DB::connection((new Dictionary)->getConnection()->getName())
				->collection((new Dictionary)->getTable())
				->insert($newEntries);
		}
	}

	private function doesntHave(array $entry)
	{
		return !Dictionary::where('group', $this->group)
			->where('code', $entry['code'])
			->where('name', $entry['name'])
			->exists();
	}

	abstract protected function getEntries();
}

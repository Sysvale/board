<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
			$this->call(
				[
					TeamSeeder::class,
					BoardSeeder::class,
					LabelSeeder::class,
					BoardListSeeder::class,
					MemberSeeder::class,
				]
			);
	}
}

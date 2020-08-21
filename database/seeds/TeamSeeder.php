<?php

use Illuminate\Database\Seeder;
use App\Models\Team;

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
            $list->save();
        }
      );
    }

  private function getDefaultTeams()
  {
    return [
      [
        'name' => 'The Avengers',
      ],
      [
        'name' => 'Stepper Labs',
      ],
    ];
  }
}

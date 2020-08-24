<?php

use App\Constants\TeamKeys;
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
            $list->key = $item['key'];
            $list->save();
        }
      );
    }

  private function getDefaultTeams()
  {
    return [
      [
        'name' => 'The Avengers',
        'key' => TeamKeys::AVENGERS,
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

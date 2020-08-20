<?php

use Illuminate\Database\Seeder;
use App\Constants\BoardListsKeys;
use App\Models\BoardList;

class BoardListSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
    public function run()
    {
      $defaultLists = collect($this->getPlanningLists());

      $defaultLists->each(function ($item) {
            $list = new BoardList();
            $list->name = $item['name'];
            $list->key = $item['key'];
            $list->user_story_holder = $item['user_story_holder'] ?? null;
            $list->save();
        }
      );
    }

  private function getDefaultLists()
  {
    return [
      [
        'name' => 'To Do',
        'key' => BoardListsKeys::TODO,
      ],
      [
        'name' => 'Em desenvolvimento',
        'key' => BoardListsKeys::DEVELOPMENT,
      ],
      [
        'name' => 'Code Review',
        'key' => BoardListsKeys::DEVELOPMENT,
      ],
      [
        'name' => 'Done/To Release',
        'key' => BoardListsKeys::DONE,
      ],
      [
        'name' => 'Deploy',
        'key' => BoardListsKeys::DEPLOY,
      ],
    ];
  }

  private function getPlanningLists()
  {
    return [
      [
        'name' => 'Bugs',
        'key' => BoardListsKeys::BUGS,
      ],
      [
        'name' => 'Devlog',
        'key' => BoardListsKeys::DEVLOG,
      ],
      [
        'name' => 'Backlog',
        'key' => BoardListsKeys::BACKLOG,
        'user_story_holder' => true,
      ],
      [
        'name' => 'Sprint',
        'key' => BoardListsKeys::SPRINT_BACKLOG,
        'user_story_holder' => true,
      ],
    ];
  }
}

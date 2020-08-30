<?php

use Illuminate\Database\Seeder;
use App\Constants\BoardListsKeys;
use App\Models\BoardList;
use App\Models\Team;

class BoardListSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
    public function run()
    {
        $lists = collect(
            array_merge(
                $this->getDefaultPlanningLists()->toArray(),
                $this->getDefaultLists()->toArray(),
                $this->getSprintListsFromTeams()->toArray(),
                $this->getDefaultProblemsLists()->toArray(),
                $this->getDevSprintListsFromTeams()->toArray()
            )
        );

        $lists->each(function ($item) {
              $list = new BoardList();
              $list->name = $item['name'];
              $list->key = $item['key'];
              $list->position = $item['position'];
              $list->user_story_holder = $item['user_story_holder'] ?? null;
              $list->save();
            }
        );
    }

  private function getDefaultLists()
  {
      return collect([
          [
            'name' => 'To Do',
            'key' => BoardListsKeys::TODO,
            'position' => 10,
          ],
          [
            'name' => 'Em desenvolvimento',
            'key' => BoardListsKeys::DEVELOPMENT,
            'position' => 11,
          ],
          [
            'name' => 'Code Review',
            'key' => BoardListsKeys::DEVELOPMENT,
            'position' => 12,
          ],
          [
            'name' => 'Done',
            'key' => BoardListsKeys::REVIEWED,
            'position' => 13,
          ],
          [
            'name' => 'P.O. Review',
            'key' => BoardListsKeys::PO_REVIEW,
            'position' => 14,
          ],
          [
            'name' => 'Done/To Release',
            'key' => BoardListsKeys::DONE,
            'position' => 15,
          ],
          [
            'name' => 'Deploy',
            'key' => BoardListsKeys::DEPLOY,
            'position' => 16,
          ],
      ]);
  }

  private function getDefaultPlanningLists()
  {
      return collect([
          [
            'name' => 'Backlog',
            'key' => BoardListsKeys::BACKLOG,
            'user_story_holder' => true,
            'position' => 4,
          ],
      ]);
  }

  private function getSprintListsFromTeams()
  {
      $teams = Team::get();
      return $teams->map(function ($item) {
          return [
            'name' => 'Sprint - '. $item->name,
            'key' => $item->key,
            'user_story_holder' => true,
            'position' => 3,
          ];
      }) ?? collect([]);
  }

  private function getDefaultProblemsLists() {
      return collect([
          [
            'name' => 'Bugs',
            'key' => BoardListsKeys::BUGS,
            'position' => 0
          ],
          [
            'name' => 'Suporte',
            'key' => BoardListsKeys::HELPDESK,
            'position' => 1,
          ],
          [
            'name' => 'Atividades dev',
            'key' => BoardListsKeys::DEVTASK,
            'position' => 2,
          ],
      ]);
  }

  private function getDevSprintListsFromTeams()
  {
      $teams = Team::get();
      return $teams->map(function ($item) {
          return [
            'name' => 'Sprint Dev - '. $item->name,
            'key' => $item->key . 'Dev',
            'position' => 3,
          ];
      }) ?? collect([]);
  }
}

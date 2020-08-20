<?php

use Illuminate\Database\Seeder;
use App\Models\Member;

class MemberSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
    public function run()
    {
      $defaultMembers = collect($this->getDefaultMembers());

      $defaultMembers->each(function ($item) {
            $list = new Member();
            $list->name = $item['name'];
            $list->save();
        }
      );
    }

  private function getDefaultMembers()
  {
    return [
      [
        'name' => 'Tássio Caique',
      ],
      [
        'name' => 'Leonardo Cavalcante',
      ],
      [
        'name' => 'Rafa Dias',
      ],
      [
        'name' => 'Patrícia Coelho',
      ],
      [
        'name' => 'Jedsão Melo',
      ],
    ];
  }
}

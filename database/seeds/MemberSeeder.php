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
      collect($this->getDefaultMembers())
        ->each(function ($item) {
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
      [
        'name' => 'Lucas Nascimento',
      ],
      [
        'name' => 'Gabriel Gomes',
      ],
      [
        'name' => 'Gilmara Castro',
      ],
      [
        'name' => 'Elayne Lemos',
      ],
      [
        'name' => 'Ueslei Melo',
      ],
      [
        'name' => 'Geidsão Benício',
      ],
      [
        'name' => 'Dani Reis',
      ],
      [
        'name' => 'Cadu Guimarães',
      ],
      [
        'name' => 'Lean Gonçalves',
      ],
    ];
  }
}

<?php

use Illuminate\Database\Seeder;
use App\Constants\LabelKeys;
use App\Models\Label;

class LabelSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
    public function run()
    {
      $defaultLables = collect($this->getDefaultLabels());

      $defaultLables->each(function ($item) {
            $list = new Label();
            $list->name = $item['name'];
            $list->key = $item['key'];
            $list->color = $item['color'];
            $list->text_color = $item['text_color'];
            $list->save();
        }
      );
    }

  private function getDefaultLabels()
  {
    return [
      [
        'name' => 'FrontEnd',
        'key' => LabelKeys::FRONTEND,
        'color' => '#815AA5',
        'text_color' => '#fff',
      ],
      [
        'name' => 'BackEnd',
        'key' => LabelKeys::BACKEND,
        'color' => '#3171A3',
        'text_color' => '#fff',
      ],
      [
        'name' => 'Mockup',
        'key' => LabelKeys::MOCKUP,
        'color' => '#1DCA94',
        'text_color' => '#fff',
      ],
      [
        'name' => 'Segurança',
        'key' => LabelKeys::SECURITY,
        'color' => '#FFAA2B',
        'text_color' => 'rgba(0, 0, 0, 0.75)',
      ],
      [
        'name' => 'Bug',
        'key' => LabelKeys::BUG,
        'color' => '#CC2A48',
        'text_color' => 'rgba(0, 0, 0, 0.75)',
      ],
      [
        'name' => 'Documentação',
        'key' => LabelKeys::DOCUMENTATION,
        'color' => '#21222E',
        'text_color' => '#fff',
      ],
      [
        'name' => 'Infra',
        'key' => LabelKeys::INFRA,
        'color' => '#028D29',
        'text_color' => '#fff',
      ],
      [
        'name' => 'Suporte',
        'key' => LabelKeys::HELP_DESK,
        'color' => '#45D9ED',
        'text_color' => 'rgba(0, 0, 0, 0.75)',
      ],
      [
        'name' => 'Reunião',
        'key' => LabelKeys::MEET,
        'color' => '#EC6646',
        'text_color' => '#fff',
      ],
      [
        'name' => 'UX',
        'key' => LabelKeys::UX,
        'color' => '#EC6646',
        'text_color' => '#fff',
      ],
      [
        'name' => 'App',
        'key' => LabelKeys::APP,
        'color' => '#EC6646',
        'text_color' => '#fff',
      ],
      [
        'name' => 'Exportação',
        'key' => LabelKeys::EXPORT,
        'color' => '#EC6646',
        'text_color' => '#fff',
      ],
    ];
  }
}

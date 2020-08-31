<?php

namespace App\Console\Commands;

use App\Constants\BoardListsKeys;
use App\Models\BoardList;
use Illuminate\Console\Command;

class CreateDevlogList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:devlog';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[Temporário] Cria lista devlog e ajusta posições das demais listas...';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (BoardList::where('key', BoardListsKeys::DEVLOG)->count()) {
            $this->info('Já existe a lista devlog na base');
            return;
        }

        $list = BoardList::create([
            'name' => 'Devlog',
            'key' => BoardListsKeys::DEVLOG,
            'position' => 3,
        ]);

        $this->info('Lista Devlog criada');

        $dev_boards = BoardList::where(
            'key',
            'regexp',
            "/([a-zA-Z0-9()]*)(Dev)/"
        )->get();

        $dev_boards->each(function ($item) {
            $this->info($item->name . ' -  old: ' . $item->position);
        });
        
        $dev_boards->each(function ($item) {
            $item->position = $item->position + 1;
            $item->save();
        });

        $dev_boards->each(function ($item) {
            $this->info($item->name . ' -  new: ' . $item->position);
        });
    }
}

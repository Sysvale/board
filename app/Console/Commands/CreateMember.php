<?php

namespace App\Console\Commands;

use App\Constants\BoardListsKeys;
use App\Models\BoardList;
use App\Models\Card;
use App\Models\Member;
use Illuminate\Console\Command;

class CreateMember extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:member {name : Nome da pessoa}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para criação de membros';

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
        $member = Member::create([
            'name' => $this->argument('name'),
        ]);

        if ($member) {
            $this->info('Membro "' . $member->name . '" criado com sucesso!');
        }
    }
}

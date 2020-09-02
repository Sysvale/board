<?php

namespace App\Console\Commands;

use App\Models\Member;
use Illuminate\Console\Command;

class CreateMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:members {names*}';

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
        $names = collect($this->argument('names'));
        $names->each(
            function ($name) {
                $member = Member::create([
                    'name' => $name,
                ]);
                if ($member) {
                    $this->info('Membro "' . $member->name . '" criado com sucesso!');
                }
            }
        );
    }
}

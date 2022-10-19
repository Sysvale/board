<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Member;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Workspace;
use Illuminate\Console\Command;

class FixNoCompany extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:no-company';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Vincula a Sysvale todos os membros, times e 
        workspaces sem empresa vinculada";

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
        $company = Company::firstOrCreate(
            array(
                'name' => 'Sysvale',
                'cnpj' => '20750009000127',
                'phone' => '7436116061',
                'email' => 'contato@sysvale.com'
            )
        );

        dump($company->name);

        $members_to_add_company = Member::where('company_id', null)->get();

        foreach ($members_to_add_company as $member) {
            if(!$member->company()->exists()) {
                dump("Membro atualizado: $member->name");
                $member->company()->associate($company->id);
                $member->save();
            }
                
        }

        $teams_to_add_company = Team::where('company_id', null)->get();

        foreach ($teams_to_add_company as $team) {
            if(!$team->company()->exists()) {
                dump("Time atualizado: $team->name");
                $team->company()->associate($company->id);
                $team->save();
            }
                
        }

        $teams_to_add_company = TeamMember::where('company_id', null)->get();

        foreach ($teams_to_add_company as $team) {
            if(!$team->company()->exists()) {
                dump("Time atualizado: $team->id");
                $team->company()->associate($company->id);
                $team->save();
            }
                
        }

        $workspaces_to_add_company = Workspace::where('company_id', null)->get();

        foreach ($workspaces_to_add_company as $workspace) {
            if(!$workspace->company()->exists()) {
                dump("Workspace atualizado: $workspace->name");
                $workspace->company()->associate($company->id);
                $workspace->save();
            }
                
        }
    }
}
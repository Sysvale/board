<?php

namespace App\Console\Commands;

use App\Models\Card;
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
                'email' => 'contato@sysvale.com',
                'email_sufix' => '@sysvale.com',
            )
        );

        $this->info($company->name);

        $members_to_add_company = Member::where('company_id', null)->get();

        foreach ($members_to_add_company as $member) {
            if(!$member->company()->exists()) {
                $member->company_id = $company->id;
                $member->save();

                $this->info("Membro atualizado: $member->name");
            }
        }

        $teams_to_add_company = Team::where('company_id', null)->get();

        foreach ($teams_to_add_company as $team) {
            if(!$team->company()->exists()) {
                $team->company_id = $company->id;
                $team->save();
                
                $this->info("Time atualizado: $team->name");
            }
        }

        $teams_members_to_add_company = TeamMember::where('company_id', null)->get();

        foreach ($teams_members_to_add_company as $team_member) {
            if(!$team_member->company()->exists()) {
                $team_member->company_id = $company->id;
                $team_member->save();
                
                $this->info("Time atualizado: $team_member->id");
            }
        }

        $cards_to_add_company = Card::where('company_id', null)->get();

        foreach ($cards_to_add_company as $card) {
            if(!$card->company()->exists()) {
                $card->company_id = $company->id;
                $card->save();
                
                $this->info("Card atualizado: $card->name");
            }
        }

        $workspaces_to_add_company = Workspace::where('company_id', null)->get();

        foreach ($workspaces_to_add_company as $workspace) {
            if(!$workspace->company()->exists()) {
                $workspace->company_id = $company->id;
                $workspace->save();
                
                $this->info("Workspace atualizado: $workspace->name");
            }
        }
    }
}

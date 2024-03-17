<?php

namespace Tests\Feature\Console\Commands;

use Illuminate\Support\Facades\Artisan;
use App\Models\Member;
use App\Models\Workspace;
use Tests\TestCase;
use App\Models\User;

class FixNoCompanyTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIfCommandAddCompanyInAllMembers()
    {
        factory(User::class, 5)->state('with-member')->create();

        Artisan::call('fix:no-company');

        $members = Member::get()->toArray();

        $updated_members = array_reduce($members, function($members_with_company, $member) {
            if (key_exists('company_id', $member)) {
                $members_with_company[] = $member;
                return $members_with_company;
            }
        });

        $this->assertCount(5, $updated_members);
    }

    public function testsIfCommandNotAddCompanyInMembersThatAlreadyHaveCompany()
    {
        $members_companies_ids = factory(Member::class, 5)
            ->state('with-company')
            ->create()
            ->pluck('company_id')
            ->toArray();

        Artisan::call('fix:no-company');

        $members = Member::get()->pluck('company_id')->toArray();

        $this->assertEqualsCanonicalizing($members_companies_ids, $members);
    }


    public function testIfCommandAddCompanyInAllWorkspaces()
    {
        factory(Workspace::class, 5)->create();

        Artisan::call('fix:no-company');

        $workspaces = Workspace::get()->toArray();

        $updated_workspaces = array_reduce($workspaces, function($workspaces_with_company, $workspace) {
            if (key_exists('company_id', $workspace)) {
                $workspaces_with_company[] = $workspace;
                return $workspaces_with_company;
            }
        });

        $this->assertCount(5, $updated_workspaces);
    }

}

<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use App\Models\Member;
use Tests\TestCase;
use App\User;

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
}

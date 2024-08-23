<?php

namespace Tests\Feature\App\Observers;

use App\Http\Middleware\ResolveCompanyTenant;
use App\Models\Card;
use App\Models\Company;
use App\Models\CompanyClient;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;
use Lcobucci\JWT\Parser;
use Sysvale\Mongodb\Passport\Client;
use Tests\TestCase;

class CardObserverTest extends TestCase
{
	public function testLinkAuthUserAndCompanyToCreatingCard(): void
	{
		$user = factory(User::class)->state('with-member-company')->create();
		Auth::login($user);
		request()->merge(
			app(ResolveCompanyTenant::class)->handle(request(), fn ($request) => $request)->all()
		);

		$card = factory(Card::class)->create();

		$this->assertEquals($user->id, $card->user_id);
		$this->assertTrue($user->member->company->is($card->company));
	}

	public function testLinkCompanyAndNullUserWhenCreatingCardUsingClient(): void
	{
		$company = factory(Company::class)->create();
		$client = Client::create();
		CompanyClient::create([
			'company_id' => $company->id,
			'client_id' =>  $client->id,
		]);

		Passport::actingAsClient($client);
		$this->mock(Parser::class, function ($mock) {
			$mock->shouldReceive('parse->claims->get')->andReturn('access_token');
		});
		request()->headers->add(['Authorization' => 'Bearer access_token']);
		request()->merge(
			app(ResolveCompanyTenant::class)->handle(request(), fn ($request) => $request)->all()
		);

		$card = factory(Card::class)->create();

		$this->assertNull($card->user_id);
		$this->assertEquals($company->id, $card->company_id);
	}
}

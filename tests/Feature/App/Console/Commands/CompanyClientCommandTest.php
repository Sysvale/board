<?php

namespace Tests\Feature\App\Console\Commands;

use App\Models\Company;
use App\Models\CompanyClient;
use Sysvale\Mongodb\Passport\Client;
use Tests\TestCase;

class CompanyClientCommandTest extends TestCase
{
	public function testCreateClientCompanyWithRelations(): void
	{
		$company = factory(Company::class)->create();

		$this->artisan('passport:company', ['company_id' => $company->id])->assertExitCode(0);

		$this->assertDatabaseHas('company_clients', ['company_id' => $company->id]);

		$company_client = CompanyClient::whereCompanyId($company->id)->first();
		$this->assertTrue($company->is($company_client->company));
		$this->assertNotNull($company_client->client_id);
		$this->assertInstanceOf(Client::class, $company_client->client);
	}

	public function testDoNotCreateNewClientForCompanyThatAlreadyHasClient(): void
	{
		$company_client = CompanyClient::create([
			'company_id' => factory(Company::class)->create()->id,
			'client_id' => Client::create([
				'id' => 'client_id',
				'secret' => 'client_secret',
			])->id,
		]);

		$this->artisan('passport:company', ['company_id' => $company_client->company_id])
			->assertExitCode(0);

		$this->assertEquals(1, CompanyClient::whereCompanyId($company_client->company_id)->count());
	}

	public function testShowClientIdAndClientSecret(): void
	{
		$client = Client::create([
			'secret' => 'client_secret',
		]);

		$company_client = CompanyClient::create([
			'company_id' => factory(Company::class)->create()->id,
			'client_id' => $client->id,
		]);

		$this->artisan('passport:company', ['company_id' => $company_client->company_id])
			->expectsOutput('Client ID: ' . $client->id)
			->expectsOutput('Client Secret: client_secret')
			->assertExitCode(0);
	}
}

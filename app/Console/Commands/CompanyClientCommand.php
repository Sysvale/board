<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\CompanyClient;
use Illuminate\Console\Command;
use RuntimeException;
use Symfony\Component\Process\Process;

class CompanyClientCommand extends Command
{
	protected $signature = 'passport:company {company_id}';
	protected $description = 'Create client credential for specific company';

	public function handle(): void
	{
		$company = Company::find($this->argument('company_id'));
		if (!$company) {
			$this->error('Company not found');
			return;
		}

		$company_client = CompanyClient::firstOrNew(['company_id' => $company->id]);

		if (!$company_client->client) {
			try {
				$client = $this->createClient();
				$company_client->client_id = $client['client_id'];
				$company_client->save();
			} catch (RuntimeException $exception) {
				$this->error($exception->getMessage());
				return;
			}
		}

		$this->line('Client ID: ' . $company_client->client_id);
		$this->line('Client Secret: ' . $company_client->refresh()->client->secret);
	}

	private function createClient(): array
	{
		$process = new Process(['php', 'artisan', 'passport:client', '--client']);
		$process->run();

		throw_if(!$process->isSuccessful(), RuntimeException::class, $process->getErrorOutput());

		$output = $process->getOutput();

		if (!preg_match('/Client ID: (.+)\nClient secret: (.+)\n/', $output, $matches)) {
			throw new RuntimeException('Não foi possível extrair o client_id e o client_secret.');
		}

		return [
			'client_id' => $matches[1],
			'client_secret' => $matches[2],
		];
	}
}

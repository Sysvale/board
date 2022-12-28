<?php

namespace App\Console\Commands;

use App\User;
use App\Constants\BoardListsKeys;
use App\Constants\BoardKeys;
use App\Constants\CardTypes;
use App\Models\BoardList;
use App\Models\Board;
use App\Models\Member;
use App\Models\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SetupInnovationCompanyCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'setup:innovation-company';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Faz as configurações iniciais da compania Innovation BI';

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
		$this->info('Iniciando...');

		$company = $this->createACompany();
		$member = $this->createAMember($company);
		$this->createAnUser($member);


		$this->info('Finalizado!');
	}

	private function createACompany()
	{
		$this->titleDivider('Criando empresa/compania');
		$company = Company::create([
			'name' => 'Innovation BI',
			'cnpj' => '00000000000000',
			'phone' => '0000000000',
			'email' => 'company@company.com',
		]);
	
		return $company;
	}

	private function createAMember($company)
	{
		$this->titleDivider('Criando membros');

		$member = Member::create([
			'name' => 'Administrador',
		]);

		$member->company()->associate($company->id);

		$member->save();
		return $member;
	}

	private function createAnUser($member)
	{
		$this->titleDivider('Criando usuário padrão...');

		return User::create([
			'email' => 'eugenio@innovationbi.com.br',
			'password' => Hash::make('BORHEWqNt7j9j4ltmbk5x1dfXFRDwAQp'),
			'member_id' => $member->id,
		]);
	}

	private function titleDivider($text)
	{
		$this->info('----' . PHP_EOL);
		$this->info($text . PHP_EOL);
	}
}

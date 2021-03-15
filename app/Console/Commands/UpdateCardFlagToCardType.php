<?php

namespace App\Console\Commands;

use App\Models\Card;
use App\Models\Team;
use App\Models\BoardList;
use App\Constants\CardTypes;
use Illuminate\Console\Command;
use App\Constants\BoardListsKeys;

class UpdateCardFlagToCardType extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'card:update-flags-to-type';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '[TEMPORÁRIO] Atualiza os tipos de cards';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$this->warn("Iniciando...\n");

		$this->warn('Atualizando histórias de usuário...');
		$this->userStories()->update(['type' => CardTypes::USER_STORY]);
		$this->userStories()->unset('is_user_story');
		$this->info("Cards de história de usuário atualizados!\n");

		$this->warn('Atualizando tarefas...');
		$this->tasks()->update(['type' => CardTypes::TASK]);
		$this->tasks()->unset('is_user_story');
		$this->info("Cards de tarefa atualizados!\n");

		$this->warn('Atualizando listas...');
		$this->addAcceptsTypeInBoardList();
		$this->info('Listas atualizadas!');

		$this->info("\nFeito!");
	}

	protected function tasks()
	{
		return Card::whereNull('type')
			->where(function ($query) {
				return $query->whereNull('is_user_story')
					->orWhere('is_user_story', false);
			});
	}

	protected function userStories()
	{
		return Card::where('is_user_story', true);
	}

	protected function addAcceptsTypeInBoardList()
	{
		$this->getPlanningBoardLists()->update(['accepts_card_type' => CardTypes::USER_STORY]);

		BoardList::whereNull('accepts_card_type')
			->update(['accepts_card_type' => CardTypes::TASK]);
	}

	protected function getPlanningBoardLists()
	{
		$team_keys = Team::get()
			->map(function ($item) {
				return $item->key;
			})->toArray();

		$planningLists = array_merge(
			[BoardListsKeys::BACKLOG],
			$team_keys
		);

		return BoardList::whereIn('key', $planningLists);
	}
}

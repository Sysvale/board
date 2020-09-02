<?php

namespace App\Console\Commands;

use App\Constants\BoardListsKeys;
use App\Models\BoardList;
use App\Models\Card;
use Illuminate\Console\Command;

class FixExistingIssuesCommand extends Command
{
	protected $signature = 'issues:put-gitlab-id';
	protected $description = '[Temporário] Coloca o gitlab_id em issues salvas';

	public function __construct()
	{
		parent::__construct();
	}

	public function handle()
	{
		$cards = Card::fromGitlab()->get(['link']);

		$bar = $this->output->createProgressBar($cards->count());
		$bar->start();

		$cards->each(function ($card) use ($bar) {
			$link = $card->link;

			preg_match("/(\d+)/", $link, $matches);
			$gitlab_id = $matches[1];

			$card->gitlab_id = (int)$gitlab_id;
			$card->save();

			$bar->advance();
		});

		$bar->finish();
	}
}

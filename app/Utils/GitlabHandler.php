<?php

namespace App\Utils;

use Gitlab;

class GitlabHandler
{
	const LINK_FIELD = 'web_url';
	const LABELS_FIELD = 'labels';
	const TITLE_FIELD = 'title';
	const ID_FIELD = 'iid';

	protected $client;

	public function __construct()
	{
		$this->client = new Gitlab\Client();
		$this->client->authenticate(env('GITLAB_TOKEN'), Gitlab\Client::AUTH_HTTP_TOKEN);
	}

	public function getAllIssues()
	{
		$pager = new Gitlab\ResultPager($this->client);

		return collect($pager->fetchAll(
			$this->client->issues(),
			'all',
			[env('GITLAB_PROJECT_ID'), ['state' => 'opened']]
		));
	}
}

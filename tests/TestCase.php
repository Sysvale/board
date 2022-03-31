<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use DB;

abstract class TestCase extends BaseTestCase
{
	use CreatesApplication;

	protected function setUp(): void
	{
		parent::setUp();
	}

	protected function tearDown(): void
	{
		$this->clearDB('mongodb');

		parent::tearDown();
	}

	private function clearDB($connection): void
	{
		foreach (DB::connection($connection)->listCollections() as $coll) {
			DB::connection($connection)
				->table($coll->getName())
				->delete();
		}
	}
}

<?php namespace App\Constants;

use App\Library\Utils\BagOfConstants;

class UserStoryStatuses extends BagOfConstants
{
	const IN_PROGRESS = 'in-progress';
	const DONE = 'done';
	const CANCELED = 'canceled';
	const NOT_STARTED = 'not-started';
}
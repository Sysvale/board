<?php namespace App\Constants;

use App\Library\Utils\BagOfConstants;

class BoardListsKeys extends BagOfConstants
{
	const TODO = 'todo';
	const DEVELOPMENT = 'development';
	const DOING = 'doing';
	const CODE_REVIEW = 'codeReview';
	const DONE = 'done';
	const DEPLOY = 'deploy';
	const BACKLOG = 'backlog';
	const PO_REVIEW = 'poReview';
	const REVIEWED = 'reviewed';
	const NOT_PRIORITIZED = 'notPrioritized';

	const TODO_DT = 'todoDt';
	const DOING_DT_NORMAL = 'doingDtNormal';
	const WAITING_ONE_APPROVE = 'waitingOneApprove';
	const WAITING_TWO_APPROVES = 'waitingTwoApproves';

	const DEFAULT_LISTS = [
		self::TODO,
		self::DEVELOPMENT,
		self::CODE_REVIEW,
		self::DONE,
		self::DEPLOY
	];

	const SHORTED_LISTS = [
		self::TODO,
		self::DOING,
		self::DONE,
	];

	const EXTENDED_LISTS = [
		self::PO_REVIEW,
		self::REVIEWED
	];

	const DT_LISTS = [
		self::TODO_DT,
		self::DOING_DT_NORMAL,
		self::WAITING_ONE_APPROVE,
		self::WAITING_TWO_APPROVES,
		self::DEPLOY,
	];
}

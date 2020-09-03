<?php namespace App\Constants;

use App\Library\Utils\BagOfConstants;

class BoardListsKeys extends BagOfConstants
{
	const TODO = 'todo';
	const DEVELOPMENT = 'development';
	const CODE_REVIEW = 'codeReview';
	const DONE = 'done';
	const DEPLOY = 'deploy';
	const BUGS = 'bugs';
	const DEVLOG = 'devlog';
	const BACKLOG = 'backlog';
	const HELPDESK = 'helpDesk';
	const DEVTASK = 'devtask';
	const PO_REVIEW = 'poReview';
	const REVIEWED = 'reviewed';
	const DESIGN_SYSTEM = 'designSystem';
	const SYS_SECURITY = 'sysSecurity';

	const DEFAULT_LISTS = [
		self::TODO,
		self::DEVELOPMENT,
		self::CODE_REVIEW,
		self::DONE,
		self::DEPLOY
	];

	const EXTENDED_LISTS = [
		self::PO_REVIEW,
		self::REVIEWED
	];

	const DEFAULT_ISSUES_LISTS = [
		self::BUGS,
		self::HELPDESK,
		self::DEVTASK,
		self::DESIGN_SYSTEM,
		self::SYS_SECURITY,
		self::DEVLOG,
	];
}

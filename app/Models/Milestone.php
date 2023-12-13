<?php

namespace App\Models;

use App\User;
use App\Constants\CardTypes;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Milestone extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'title',
		'description',
		'start_date',
		'end_date',
		'acceptance_criteria',
		'status',
		'closed',
		'team_ids',
	];

	protected $appends = ['id'];
	protected $hidden = ['_id'];
}

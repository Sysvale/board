<?php

namespace App\Models;

use App\Models\User;
use App\Constants\CardTypes;
use  MongoDB\Laravel\Eloquent\Model;
use  MongoDB\Laravel\Eloquent\SoftDeletes;

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

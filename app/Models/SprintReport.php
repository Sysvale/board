<?php

namespace App\Models;

use App\Models\Team;
use  MongoDB\Laravel\Eloquent\Model;
use  MongoDB\Laravel\Eloquent\SoftDeletes;

class SprintReport extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'team_id',
		'members',
		'notes',
		'impediments_quantity',
		'started_at',
		'finished_at',
		'cards',
	];

	public function team()
	{
		return $this->belongsTo(Team::class);
	}
}

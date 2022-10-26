<?php

namespace App\Models;

use App\Models\Team;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class SprintReport extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'team_id',
		'members',
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

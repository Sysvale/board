<?php

namespace App\Models;

use  MongoDB\Laravel\Eloquent\Model;
use  MongoDB\Laravel\Eloquent\SoftDeletes;

class Event extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'team_id',
		'name',
		'start',
		'end',
		'members',
		'labels',
	];
	protected $appends = ['id'];
	protected $hidden = ['_id'];

	public function team()
	{
		return $this->belongsTo('App\Models\Team');
	}
}

<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Member extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'name',
		'team_id',
		'avatar_url',
	];

	protected $appends = ['id'];
	protected $hidden = ['_id'];

	public function team()
	{
		return $this->belongsTo('App\Models\Team');
	}
}

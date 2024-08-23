<?php

namespace App\Models;

use  MongoDB\Laravel\Eloquent\Model;
use  MongoDB\Laravel\Eloquent\SoftDeletes;

class Board extends Model
{
	use SoftDeletes;

	public $fillable = [
		'name',
		'position',
		'key',
		'user_story_holder'
	];

	protected $appends = ['id'];
	protected $hidden = ['_id'];

	public function cards()
	{
		return $this->hasMany('App\Models\BoardList');
	}
}

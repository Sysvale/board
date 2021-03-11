<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class BoardList extends Model
{
	use SoftDeletes;

	public $fillable = [
		'name',
		'position',
		'key',
		'user_story_holder',
		'accepts_card_type',
	];

	protected $appends = ['id'];
	protected $hidden = ['_id'];

	public function cards()
	{
		return $this->hasMany('App\Models\Card');
	}
}

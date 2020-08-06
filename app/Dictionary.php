<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Dictionary extends \Moloquent
{
	use SoftDeletes;

	protected $fillable = [
		'name',
		'group',
		'code',
	];

	protected $hidden = ['_id'];
	public $timestamps = false;
}

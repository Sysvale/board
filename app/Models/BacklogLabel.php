<?php

namespace App\Models;

use  MongoDB\Laravel\Eloquent\Model;
use  MongoDB\Laravel\Eloquent\SoftDeletes;

class BacklogLabel extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'name',
		'color',
		'text_color',
	];

	protected $appends = ['id'];
	protected $hidden = ['_id'];
}

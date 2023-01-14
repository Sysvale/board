<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

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

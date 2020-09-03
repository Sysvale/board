<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Member extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'name',
	];

	protected $appends = ['id'];
	protected $hidden = ['_id'];
}

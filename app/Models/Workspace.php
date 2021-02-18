<?php

namespace App\Models;

use App\Models\Team;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Workspace extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'name',
		'lottie_file',
	];

	protected $appends = ['id'];
	protected $hidden = ['_id'];

	public function teams()
	{
		return $this->hasMany(Team::class);
	}
}

<?php

namespace App\Models;

use App\Models\Workspace;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Team extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'name',
		'key',
		'extended_task_flow',
		'short_task_flow',
		'workspace_id',
	];

	protected $appends = ['id'];
	protected $hidden = ['_id'];

	public function cards()
	{
		return $this->hasMany('App\Models\Card');
	}

	public function workspace()
	{
		return $this->belongsTo(Workspace::class);
	}
}

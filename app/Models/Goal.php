<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Goal extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'team_id',
		'workspace_id',
		'title',
	];
	protected $appends = ['id'];
	protected $hidden = ['_id'];

	public function team()
	{
		return $this->belongsTo('App\Models\Team');
	}

	public function workspace()
	{
		return $this->belongsTo('App\Models\Workspace');
	}
}

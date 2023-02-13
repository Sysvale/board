<?php

namespace App\Models;

use App\Models\Team;
use App\Models\Label;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Process extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'name',
		'team_ids',
		'checklists',
	];

	protected $appends = ['id'];
	protected $hidden = ['_id'];

	public function teams()
	{
		return $this->hasMany(Team::class);
	}

	public function company()
	{
		return $this->belongsTo(Company::class);
	}
}

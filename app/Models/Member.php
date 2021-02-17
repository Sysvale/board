<?php

namespace App\Models;

use App\Models\TeamMember;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Member extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'name',
		'avatar_url',
	];

	protected $appends = ['id'];
	protected $hidden = ['_id'];

	public function teamMembers()
	{
		return $this->hasMany(TeamMember::class);
	}

	public function getTeams()
	{
		return $this->teamMembers->map(function ($pivot) {
			return $pivot->team;
		});
	}

	public function getTeamIdsAttribute()
	{
		return $this->getTeams()->pluck('id')->toArray();
	}

	public function getWorkspaceIdsAttribute()
	{
		return $this->getTeams()->pluck('workspace_id')->toArray();
	}
}

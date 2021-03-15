<?php

namespace App\Models;

use App\User;
use App\Models\TeamMember;
use Illuminate\Support\Str;
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

	public function user()
	{
		return $this->hasOne(User::class, 'member_id');
	}

	public function getFullEmailAttribute()
	{
		return optional($this->user)->email ?? '';
	}

	public function getEmailAttribute()
	{
		return Str::before($this->full_email, '@sysvale.com');
	}

	public function getHasLoginAttribute()
	{
		return !!optional($this->user)->configured_password;
	}

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

<?php

namespace App\Models;

use App\Models\User;
use App\Models\TeamMember;
use Illuminate\Support\Str;
use  MongoDB\Laravel\Eloquent\Model;
use  MongoDB\Laravel\Eloquent\SoftDeletes;

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

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function getFullEmailAttribute()
	{
		return optional($this->user)->email ?? '';
	}

	public function getEmailAttribute()
	{
		return $this->full_email;
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

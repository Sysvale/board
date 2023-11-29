<?php

namespace App\Models;

use App\Models\Team;
use App\Models\Member;
use  MongoDB\Laravel\Eloquent\Model;
use  MongoDB\Laravel\Eloquent\SoftDeletes;

class TeamMember extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'team_id',
		'member_id',
	];

	public function team()
	{
		return $this->belongsTo(Team::class);
	}

	public function member()
	{
		return $this->belongsTo(Member::class);
	}

	public function company()
	{
		return $this->belongsTo(Company::class);
	}
}

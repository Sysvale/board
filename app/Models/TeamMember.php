<?php

namespace App\Models;

use App\Models\Team;
use App\Models\Member;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

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
}

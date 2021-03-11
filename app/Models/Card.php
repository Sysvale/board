<?php

namespace App\Models;

use App\Constants\CardTypes;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Card extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'board_list_id',
		'team_id',
		'board_id',
		'user_story_id',
		'title',
		'link',
		'position',
		'members',
		'labels',
		'acceptance_criteria',
		'checklist',
		'type',
		'gitlab_id',
		'workspace_id',
	];

	protected $appends = ['id', 'is_user_story', 'is_not_priorized', 'is_task'];
	protected $hidden = ['_id', 'board_list']; //esse segundo n sei como parar de mandar a parada kkk

	//relacionamento com members????????
	//relacionamento com labels????????

	public function boardList()
	{
		return $this->belongsTo('App\Models\BoardList');
	}

	public function team()
	{
		return $this->belongsTo('App\Models\Team');
	}

	public function userStory()
	{
		return $this->belongsTo('App\Models\Card');
	}

	public function board()
	{
		return $this->belongsTo('App\Models\Board');
	}

	public function scopeFromGitlab($query)
	{
		return $query->where('from_gitlab', true);
	}

	public function getIsUserStoryAttribute()
	{
		return $this->type === CardTypes::USER_STORY;
	}

	public function getIsTaskAttribute()
	{
		return $this->type === CardTypes::TASK;
	}

	public function getIsNotPrioritizedAttribute()
	{
		return $this->type === CardTypes::NOT_PRIORITIZED;
	}
}

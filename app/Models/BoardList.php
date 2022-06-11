<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class BoardList extends Model
{
	use SoftDeletes;

	public $fillable = [
		'name',
		'position',
		'key',
		'user_story_holder',
		'accepts_card_type',
		'team_id',
		'is_devlog',
		'is_goalable',
	];

	protected $appends = ['id'];
	protected $hidden = ['_id'];

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('order', function (Builder $builder) {
			$builder->orderBy('position');
		});
	}

	public function cards()
	{
		return $this->hasMany('App\Models\Card');
	}

	public function team()
	{
		return $this->belongsTo('App\Models\Team');
	}
}

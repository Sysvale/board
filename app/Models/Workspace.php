<?php

namespace App\Models;

use App\Models\Team;
use App\Models\Label;
use App\Traits\Models\hasCompanyGlobalScope;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Workspace extends Model
{
	use SoftDeletes;
	use hasCompanyGlobalScope;

	protected $fillable = [
		'name',
		'lottie_file',
		'settings',
		'inactive',
	];

	protected $appends = ['id'];
	protected $hidden = ['_id'];

	public function teams()
	{
		return $this->hasMany(Team::class);
	}

	public function getTeamIdsAttribute()
	{
		return $this->teams->pluck('id')->toArray();
	}

	public function getTeamNamesAttribute()
	{
		return implode(', ', $this->teams->pluck('name')->toArray());
	}

	public function labels()
	{
		return $this->hasMany(Label::class);
	}

	public function getLabelIdsAttribute()
	{
		return $this->labels->pluck('id')->toArray();
	}

	public function syncTeams($ids)
	{
		$current = $this->teams->pluck('id')->toArray();

		$add = array_diff($ids, $current);
		$remove = array_diff($current, $ids);

		if (empty($add) && empty($remove)) {
			return;
		}

		$this->associateMany(Team::class, $add);

		foreach ($remove as $id_to_remove) {
			$team = Team::find($id_to_remove);
			$team->unset('workspace_id');
		}
	}

	public function syncLabels($ids)
	{
		$current = $this->labels->pluck('id')->toArray();

		$add = array_diff($ids, $current);
		$remove = array_diff($current, $ids);

		if (empty($add) && empty($remove)) {
			return;
		}

		$this->associateMany(Label::class, $add);

		foreach ($remove as $id_to_remove) {
			$team = Label::find($id_to_remove);
			$team->unset('workspace_id');
		}
	}

	public function associateMany($model, array $ids)
	{
		foreach ($ids as $id) {
			$register = $model::find($id);
			$register->workspace_id = $this->id;
			$register->save();
		}
	}
}

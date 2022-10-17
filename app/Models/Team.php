<?php

namespace App\Models;

use App\Models\Workspace;
use App\Models\BoardList;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use App\Services\BoardListService;
use App\Traits\Models\hasCompanyGlobalScope;

class Team extends Model
{
	use SoftDeletes;
	use hasCompanyGlobalScope;

	protected $fillable = [
		'name',
		'key',
		'extended_task_flow',
		'short_task_flow',
		'workspace_id',
	];

	protected $appends = ['id'];
	protected $hidden = ['_id'];
	
	public function cards()
	{
		return $this->hasMany(Card::class);
	}

	public function workspace()
	{
		return $this->belongsTo(Workspace::class);
	}

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function board_lists()
	{
		return $this->hasMany(BoardList::class);
	}

	public function syncBoardLists($from_request)
	{

		$current = $this->board_lists;

		$add = array_filter($from_request, function ($item) {
			return empty($item['id']);
		});

		$update = array_filter($from_request, function ($item) {
			return !empty($item['id']);
		});

		$remove = array_diff(
			$current->pluck('id')->toArray(),
			collect($from_request)->pluck('id')->toArray()
		);

		
		if (empty($add) && empty($remove) && empty($update)) {
			return;
		}

		$board_list_service = new BoardListService();
		$board_list_service->createMany($add, $this->id);
		$board_list_service->deleteMany($remove);
		$board_list_service->updateMany($update);
	}
}

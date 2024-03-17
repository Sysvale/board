<?php

namespace App\Models;

use App\Models\Workspace;
use  MongoDB\Laravel\Eloquent\Model;
use  MongoDB\Laravel\Eloquent\SoftDeletes;

class Label extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'name',
		'key',
		'color',
		'text_color',
		'workspace_id',
	];

	protected $appends = ['id'];
	protected $hidden = ['_id'];

	public function workspace()
	{
		return $this->belongsTo(Workspace::class);
	}
}

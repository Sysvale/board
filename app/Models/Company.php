<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

	protected $fillable = [
		'cnpj',
		'name',
		'phone',
		'email',
		'email_sufix',
    ];

	public function user()
	{
		return $this->hasMany(User::class);
	}

	public function workspace()
	{
		return $this->hasMany(Workspace::class);
	}

	public function teams()
	{
		return $this->hasMany(Team::class);
	}
}

<?php

namespace App\Models;

use  MongoDB\Laravel\Eloquent\Model;
use  MongoDB\Laravel\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

	/*
		Estrutura do objeto que vai no array addons
		{
			styles: ['url_do_style_1', 'url_do_style_2'],
			scripts: ['url_do_script_1', 'url_do_script_2'],
		}
	*/

	protected $fillable = [
		'cnpj',
		'name',
		'phone',
		'email',
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

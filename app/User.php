<?php

namespace App;

use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use App\Events\UserRegisteredEvent;
use App\Models\Member;

class User extends Authenticatable
{
	use Notifiable;
	use SoftDeletes;

	protected $dispatchesEvents = [
		'created' => UserRegisteredEvent::class,
	];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
		'member_id',
		'configured_password',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	public function member()
	{
		return $this->belongsTo(Member::class);
	}
}

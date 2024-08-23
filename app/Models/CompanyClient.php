<?php

namespace App\Models;

use  MongoDB\Laravel\Eloquent\Model;
use  MongoDB\Laravel\Eloquent\SoftDeletes;
use  MongoDB\Laravel\Relations\BelongsTo;
use Sysvale\Mongodb\Passport\Client;

class CompanyClient extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'company_id',
		'client_id',
	];

	public function company(): BelongsTo
	{
		return $this->belongsTo(Company::class);
	}

	public function client(): BelongsTo
	{
		return $this->belongsTo(Client::class);
	}
}

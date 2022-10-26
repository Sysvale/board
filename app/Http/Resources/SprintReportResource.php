<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SprintReportResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'id' => $this->id,
			'started_at' => $this->started_at,
			'finished_at' => $this->finished_at,
			'members' => $this->members,
			'impediments_quantity' => $this->impediments_quantity,
			'cards' => $this->cards,
		];
	}
}

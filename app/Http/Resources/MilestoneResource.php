<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MilestoneResource extends JsonResource
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
			'title' => $this->title,
			'description' => $this->description,
			'start_date' => $this->start_date,
			'end_date' => $this->end_date,
			'team_ids' => $this->when(isset($this->team_ids), $this->team_ids),
		];
	}
}

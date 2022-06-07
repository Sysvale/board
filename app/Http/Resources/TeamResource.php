<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
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
			'name' => $this->name,
			'extended_task_flow' => $this->extended_task_flow,
			'key' => $this->key,
			'workspace_id' => $this->workspace_id,
			'board_lists' => $this->board_lists,
		];
	}
}

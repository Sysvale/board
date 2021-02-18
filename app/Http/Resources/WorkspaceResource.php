<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkspaceResource extends JsonResource
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
			'team_ids' => $this->team_ids,
			'label_ids' => $this->label_ids,
			'team_names' => $this->team_names,
			'lootie_file' => $this->lootie_file,
			'settings' => $this->settings,
		];
	}
}

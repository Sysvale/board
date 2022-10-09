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
			'lottie_file' => $this->lottie_file,
			'settings' => (object) $this->settings,
			'status' => $this->inactive ? 'Desativado' : '',
		];
	}
}

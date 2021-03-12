<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
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
			'email' => $this->email,
			'full_email' => $this->full_email,
			'has_login' => $this->has_login,
			'user_id' => optional($this->user)->id,
			'avatar_url' => $this->avatar_url,
			'teams' => implode(', ', $this->getTeams()->pluck('name')->toArray()),
			'team_ids' => $this->team_ids,
			'workspace_ids' => $this->workspace_ids,
		];
	}
}

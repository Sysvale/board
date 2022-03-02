<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
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
			'number' => $this->number,
			'created_at' => optional($this->created_at)->format('d/m/Y H:i'),
			'estimated' => $this->when(isset($this->estimated), $this->estimated),
			'board_list_id' => $this->when(isset($this->board_list_id), $this->board_list_id),
			'team_id' => $this->when(isset($this->team_id), $this->team_id),
			'board_id' => $this->when(isset($this->board_id), $this->board_id),
			'user_story_id' => $this->when(isset($this->user_story_id), $this->user_story_id),
			'title' => $this->when(isset($this->title), $this->title),
			'link' => $this->when(isset($this->link), $this->link),
			'position' => $this->when(isset($this->position), $this->position),
			'members' => $this->when(isset($this->members), $this->members),
			'labels' => $this->when(isset($this->labels), $this->labels),
			'checklist' => $this->when(isset($this->checklist), $this->checklist),
			'gitlab_id' => $this->when(isset($this->gitlab_id), $this->gitlab_id),
			'workspace_id' => $this->when(isset($this->workspace_id), $this->workspace_id),
			'description' => $this->when(isset($this->description), $this->description),
			'rating' => $this->when(isset($this->rating), $this->rating),
			'has_metric' => $this->when(isset($this->has_metric), $this->has_metric),
			'is_recurrent' => $this->when(isset($this->is_recurrent), $this->is_recurrent),
			'is_technical_work' => $this->when(isset($this->is_technical_work), $this->is_technical_work),
			'user' => $this->when(isset($this->user), $this->user),
			'status' => $this->when(isset($this->status), $this->status),
			'acceptance_criteria' => $this->when(
				isset($this->acceptance_criteria),
				$this->acceptance_criteria
			),
			'first_default_board_list_id' => $this->first_default_board_list_id,
		];
	}
}

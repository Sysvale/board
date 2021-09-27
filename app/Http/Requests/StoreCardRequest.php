<?php

namespace App\Http\Requests;

use App\Constants\CardTypes;
use Illuminate\Foundation\Http\FormRequest;

class StoreCardRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return array_merge(
			$this->globalRules(),
			$this->getRulesByType()
		);
	}

	protected function globalRules(): array
	{
		$types = CardTypes::allJoin();

		return [
			'title' => 'required|string',
			'type' => "required|in:$types",
			'board_list_id' => 'required|string',
			'user_story_id' => 'nullable|string',
			'members' => 'nullable|array',
			'team_id' => 'nullable|string',
			'board_id' => 'nullable|string',
			'labels' => 'nullable|string',
			'link' => 'nullable|string',
			'workspace_id' => 'nullable|string',
			'checklist' => 'nullable|array',
		];
	}

	protected function getRulesByType(): array
	{
		if ($this->type === CardTypes::NOT_PRIORITIZED) {
			return $this->notPrioritizedRules();
		}

		if ($this->type === CardTypes::USER_STORY) {
			return $this->userStoryRules();
		}

		return [];
	}

	protected function notPrioritizedRules(): array
	{
		return [
			'rating' => 'nullable|numeric|min:0|max:5',
			'description' => 'nullable|string',
		];
	}

	protected function userStoryRules(): array
	{
		return [
			'has_metric' => 'nullable|boolean',
			'is_recurrent' => 'nullable|boolean',
		];
	}
}

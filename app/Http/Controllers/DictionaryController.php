<?php

namespace App\Http\Controllers;

use App\Dictionary;
use Illuminate\Http\Request;

class DictionaryController extends Controller
{
	public function simpleList(Request $in)
	{
		$groups = Dictionary::whereIn('group', $in->groups)
			->orderBy('name', 'asc')
			->get(['name', 'code', 'group'])
			->groupBy('group')
			->map(function($group) {
				$group = collect($group)->map(function($item) {
					return [
						'value' => $item['code'],
						'text' => $item['name'],
					];
				});

				return $group;
			});

		if ($groups->count() == 1) {
			$groups = $groups->flatten(1);
		}

		return response($groups, 200);
	}
}
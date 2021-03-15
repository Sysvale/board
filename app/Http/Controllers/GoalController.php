<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;

class GoalController extends Controller
{
	public function index()
	{
		return Goal::all();
	}

	public function store(Request $in)
	{
		$goal = Goal::create([
			'title' => $in->title,
			'workspace_id' => $in->workspace_id,
			'team_id' => $in->team_id,
		]);

		return $goal;
	}

	public function update(Request $in, $id)
	{
		$params = [
			'title' => $in->title,
			'workspace_id' => $in->workspace_id,
			'team_id' => $in->team_id,
		];

		$goal = Goal::where('_id', $id);
		$goal->update($params);

		return $goal->get()->first();
	}

	public function destroy($id)
	{
		$goal = Goal::where('_id', $id)
			->delete();
		return $goal;
	}
}

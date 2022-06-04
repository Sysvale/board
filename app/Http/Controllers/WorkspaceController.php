<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Label;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\WorkspaceResource;
use App\Models\Goal;

class WorkspaceController extends Controller
{
	public function index()
	{
		$workspaces = Workspace::all();

		return WorkspaceResource::collection($workspaces);
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'name' => 'required|string|unique:workspaces',
			'team_ids' => 'nullable|array',
			'label_ids' => 'nullable|array',
			'lottie_file' => 'nullable|string',
			'settings' => 'nullable|array',
		]);

		$workspace = Workspace::create($data);
		$workspace->associateMany(Team::class, $data['team_ids'] ?? []);
		$workspace->associateMany(Label::class, $data['label_ids'] ?? []);

		Goal::create([
			'title' => 'Defina um objetivo',
			'workspace_id' => $workspace->id,
		]);

		return new WorkspaceResource($workspace);
	}

	public function update(Request $request, Workspace $workspace)
	{
		$data = $request->validate([
			'name' => 'required|string',
			'team_ids' => 'nullable|array',
			'label_ids' => 'nullable|array',
			'lottie_file' => 'nullable|string',
			'settings' => 'nullable|array',
		]);

		$workspace->update($data);
		$workspace->syncTeams($data['team_ids'] ?? []);
		$workspace->syncLabels($data['label_ids'] ?? []);

		return new WorkspaceResource($workspace);
	}

	public function destroy(Workspace $workspace)
	{
		$workspace->teams()->unset('workspace_id');
		$workspace->labels()->unset('workspace_id');
		$workspace->delete();

		return Response::json('Deletado com sucesso.', 200);
	}
}

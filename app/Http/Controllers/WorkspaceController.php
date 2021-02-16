<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class WorkspaceController extends Controller
{
	public function index()
	{
		$workspaces = Workspace::all();

		return $workspaces;
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'name' => 'required|string|unique:workspaces',
		]);

		$workspace = Workspace::create($data);

		return $workspace;
	}

	public function destroy(Workspace $workspace)
	{
		$workspace->delete();

		return Response::json('Deletado com sucesso.', 200);
	}
}

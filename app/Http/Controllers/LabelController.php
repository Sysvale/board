<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Workspace;
use App\Http\Resources\LabelResource;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class LabelController extends Controller
{
	public function index()
	{
		return Label::orderBy('name')->get();
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'name' => 'required|string',
			'text_color' => 'required|string',
			'color' => 'required|string',
			'workspace_id' => 'required|string',
		]);

		$label = Label::create($data);

		return new LabelResource($label);
	}

	public function update(Request $request, Label $label)
	{
		$data = $request->validate([
			'name' => 'required|string',
			'text_color' => 'required|string',
			'color' => 'required|string',
			'workspace_id' => 'required|string',
		]);

		$label->update($data);

		return new LabelResource($label);
	}

	public function destroy(Label $label)
	{
		$label->delete();

		return Response::json('Categoria excluída com sucesso!');
	}

	public function getLabelsByWorkspaceId(Workspace $workspace)
	{
		return Label::where('workspace_id', $workspace->id)->orderBy('name')->get();
	}
}

<?php

namespace App\Http\Controllers;

use App\Models\BacklogLabel;
use App\Models\Workspace;
use App\Http\Resources\BacklogLabelResource;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class BacklogLabelController extends Controller
{
	public function index()
	{
		return BacklogLabel::orderBy('name')->get();
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'name' => 'required|string',
			'text_color' => 'required|string',
			'color' => 'required|string',
		]);

		$backlog_label = BacklogLabel::create($data);

		return new BacklogLabelResource($backlog_label);
	}

	public function update(Request $request, BacklogLabel $backlog_label)
	{
		$data = $request->validate([
			'name' => 'required|string',
			'text_color' => 'required|string',
			'color' => 'required|string',
		]);
		
		$backlog_label->update($data);

		return new BacklogLabelResource($backlog_label);
	}

	public function destroy(BacklogLabel $backlog_label)
	{
		$backlog_label->delete();

		return Response::json('Categoria excluída com sucesso!');
	}
}

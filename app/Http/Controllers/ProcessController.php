<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProcessResource;
use App\Models\Process;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ProcessController extends Controller
{
	public function index()
	{
		$processes = Process::all();

		return ProcessResource::collection($processes);
	}

	public function show(Process $process)
	{
		return new ProcessResource($process);
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'name' => 'required|string|unique:processes',
			'team_ids' => 'required|array',
			'checklists' => 'required|array',
		]);

		$process = Process::create($data);

		return new ProcessResource($process);
	}

	public function update(Request $request, Process $process)
	{
		$data = $request->validate([
			'name' => 'required|string',
			'team_ids' => 'required|array',
			'checklists' => 'required|array',
		]);

		$process->update($data);

		return new ProcessResource($process);
	}

	public function destroy(Process $process)
	{
		$process->delete();

		return Response::json('Deletado com sucesso.', 200);
	}
}

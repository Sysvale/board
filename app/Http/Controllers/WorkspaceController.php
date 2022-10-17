<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Label;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\WorkspaceResource;
use App\Models\Goal;
use App\Models\BoardList;
use App\Constants\BoardListsKeys;
use App\Constants\CardTypes;
use App\Services\WorkspaceService;

class WorkspaceController extends Controller
{
	public function index(Request $request)
	{
		$workspace_service = new WorkspaceService();

		if(isset($request->with_inactive)) {
			$workspaces = $workspace_service->getWorkspacesWithInactive();
		} else {
			$workspaces = $workspace_service->getActiveWorkspaces();
		}

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
			'company_id' => 'required',
		]);

		$company_id = $request->company_id;

		$workspace = Workspace::create($data);
		$workspace->associateMany(Team::class, $data['team_ids'] ?? []);
		$workspace->associateMany(Label::class, $data['label_ids'] ?? []);
		$workspace->company()->attach($company_id);

		Goal::create([
			'title' => 'Defina um objetivo',
			'workspace_id' => $workspace->id,
		]);

		$this->createBoardList(BoardListsKeys::NOT_PRIORITIZED, $workspace, 0);
		$this->createBoardList(BoardListsKeys::BACKLOG, $workspace, 1);

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
			'inactive' => 'nullable|boolean',
		]);

		$workspace->update($data);
		$workspace->syncTeams($data['team_ids'] ?? []);
		$workspace->syncLabels($data['label_ids'] ?? []);

		$this->updateBoardList(BoardListsKeys::BACKLOG, $workspace);
		$this->updateBoardList(BoardListsKeys::NOT_PRIORITIZED, $workspace);

		return new WorkspaceResource($workspace);
	}

	public function destroy(Workspace $workspace)
	{
		$this->removeBoardList(BoardListsKeys::BACKLOG, $workspace);
		$this->removeBoardList(BoardListsKeys::NOT_PRIORITIZED, $workspace);

		$workspace->teams()->unset('workspace_id');
		$workspace->labels()->unset('workspace_id');
		$workspace->delete();

		return Response::json('Deletado com sucesso.', 200);
	}

	private function createBoardList($key, $workspace, $position)
	{
		$accepts_card_type = CardTypes::USER_STORY;
		$is_goalable = true;

		if($key === BoardListsKeys::NOT_PRIORITIZED) {
			$accepts_card_type = CardTypes::NOT_PRIORITIZED;
			$is_goalable = false;
		}

		BoardList::create([
			'name' => $this->getBoardListLabel($key, $workspace),
			'key' => $this->getBoardListKey($key, $workspace),
			'accepts_card_type' => $accepts_card_type,
			'is_goalable' => $is_goalable,
			'position' => $position,
		]);
	}

	private function updateBoardList($key, $workspace)
	{
		$board_list = BoardList::where('key', $this->getBoardListKey($key, $workspace))->first();

		$board_list->name = $this->getBoardListLabel($key, $workspace);

		$board_list->save();
	}

	private function removeBoardList($key, $workspace)
	{
		$board_list = BoardList::where('key', $this->getBoardListKey($key, $workspace))->first();

		if(!empty($board_list)) {
			$board_list->delete();
		}

	}

	private function getBoardListLabel($key, $workspace)
	{
		$label = 'Backlog';
		if($key === BoardListsKeys::NOT_PRIORITIZED) {
			$label = 'Não priorizados';
		}

		$label = $label . ' - ' . $workspace->name;

		return $label;
	}

	private function getBoardListKey($key, $workspace)
	{
		return $key.'-'.$workspace->id;
	}
}

<?php

namespace App\Http\Controllers;

use App\Constants\BoardListsKeys;
use App\Models\BoardList;
use App\Models\Team;
use App\Models\Workspace;
use Illuminate\Http\Request;
use App\Services\BoardListService;

class BoardListController extends Controller
{
	public function getTaskLists(Request $in)
	{
		return (new BoardListService())->getTaskLists($in->team_id);
	}

	public function getDevlogLists(Request $in)
	{
		return (new BoardListService())->getDevLists($in->team_id);

	}

	public function getPlanningLists(Workspace $workspace)
	{
		return (new BoardListService())->getPlanningLists($workspace->id);
	}

	public function getCompanyPlanningLists()
	{
		return (new BoardListService())->getCompanyPlanningLists();
	}
}

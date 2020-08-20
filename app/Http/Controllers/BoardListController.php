<?php

namespace App\Http\Controllers;

use App\Constants\BoardListsKeys;
use App\Models\BoardList;
use Illuminate\Http\Request;

class BoardListController extends Controller
{
    public function getDefaultLists()
    {
        $defaulLists = [
            BoardListsKeys::TODO,
            BoardListsKeys::DEVELOPMENT,
            BoardListsKeys::CODE_REVIEW,
            BoardListsKeys::DONE,
            BoardListsKeys::DEPLOY,
        ];
        return BoardList::whereIn('key', $defaulLists)->get();
    }

    public function getPlanningLists()
    {
        $planningLists = [
            BoardListsKeys::BUGS,
            BoardListsKeys::DEVLOG,
            BoardListsKeys::BACKLOG,
            BoardListsKeys::SPRINT_BACKLOG,
        ];
        return BoardList::whereIn('key', $planningLists)->get();
    }
}

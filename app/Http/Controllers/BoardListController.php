<?php

namespace App\Http\Controllers;

use App\Constants\BoardListsKeys;
use App\Models\BoardList;
use App\Models\Team;

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

    public function getDevlogLists()
    {
        $defaulLists = [
            BoardListsKeys::DEVLOG,
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
        ];

        $teams = Team::get();
        $planningLists = array_merge(
            $planningLists,
            $teams->map(
                function ($item) {
                    return $item->key;
                }
            )->toArray()
        );

        return BoardList::whereIn('key', $planningLists)->get();
    }
}

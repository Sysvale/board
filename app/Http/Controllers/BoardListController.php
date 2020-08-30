<?php

namespace App\Http\Controllers;

use App\Constants\BoardListsKeys;
use App\Models\BoardList;
use App\Models\Team;
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

    public function getDevlogLists(Request $in)
    {
        $defaulLists = [
            Team::where('_id', $in->team_id)->first()->key . 'Dev',
            BoardListsKeys::TODO,
            BoardListsKeys::DEVELOPMENT,
            BoardListsKeys::CODE_REVIEW,
            BoardListsKeys::DONE,
            BoardListsKeys::DEPLOY,
        ];

        return BoardList::whereIn('key', $defaulLists)
            ->get()
            ->sortBy('position')
            ->values();
    }

    public function getPlanningLists()
    {
        $planningLists = [
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

    public function getIssuesLists()
    {
        $issuesLists = [
            BoardListsKeys::BUGS,
            BoardListsKeys::HELPDESK,
            BoardListsKeys::DEVTASK,
        ];

        $teams = Team::get();
        $issuesLists = array_merge(
            $issuesLists,
            $teams->map(
                function ($item) {
                    return $item->key . 'Dev';
                }
            )->toArray()
        );

        return BoardList::whereIn('key', $issuesLists)->get();
    }
}

<?php

namespace App\Http\Controllers;

use App\Constants\BoardListsKeys;
use App\Models\BoardList;
use App\Models\Team;
use Illuminate\Http\Request;

class BoardListController extends Controller
{
    public function getDefaultLists(Request $in)
    {

        return BoardList::whereIn('key', $this->getDefaultTaskLists($in->team_id))
            ->get()
            ->sortBy('position')
            ->values();
    }

    public function getDevlogLists(Request $in)
    {
        $default_lists = [Team::where('_id', $in->team_id)->first()->key . 'Dev'];
        

        $default_lists = array_merge(
            $default_lists,
            $this->getDefaultTaskLists($in->team_id)
        );

        return BoardList::whereIn('key', $default_lists)
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

    private function getDefaultTaskLists($team_id)
    {
        $default_lists = [
            BoardListsKeys::TODO,
            BoardListsKeys::DEVELOPMENT,
            BoardListsKeys::CODE_REVIEW,
            BoardListsKeys::DONE,
            BoardListsKeys::DEPLOY,
        ];

        if ($team_id) {
            $extended_task_flow = Team::where('_id', $team_id)
                ->first()
                ->extended_task_flow;
            if ($extended_task_flow) {
                $default_lists = array_merge(
                    $default_lists,
                    [
                        BoardListsKeys::PO_REVIEW,
                        BoardListsKeys::REVIEWED
                    ]
                );
            }
        }

        return $default_lists;
    }
}

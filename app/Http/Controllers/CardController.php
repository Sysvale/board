<?php

namespace App\Http\Controllers;

use App\Models\BoardList;
use App\Models\Card;
use Illuminate\Http\Request;
use stdClass;

class CardController extends Controller
{
    public function getCardsByListsIds(Request $in)
    {
        $cards = Card::whereIn('board_list_id', $in->lists_ids)
            ->get();
        $payload = array();
        collect($in->lists_ids)->each(
            function ($item) use (&$payload, &$cards) {
                $payload[$item] = $cards->where('board_list_id', $item)->values();
            }
        );

        return $payload;
    }

    public function store(Request $in)
    {
        $card = Card::create([
            'board_list_id' => $in->board_list_id,
            'title' => $in->title
        ]);

        return $card;
    }

    public function update(Request $in, $id)
    {
        $params = [
            'link' => $in->link,
            'labels' => $in->labels,
            'members' => $in->members,
        ];

        $card = Card::where('_id', $id)->update($params);

        return $card;
    }

    public function destroy($id)
    {
        $card = Card::where('_id', $id)
            ->delete();
        return $card;
    }
}

<?php

namespace App\Observers;

use App\Models\Card;

class CardObserver
{
    /**
     * Handle the card "created" event.
     *
     * @param  \App\Models\Card  $card
     * @return void
     */
    public function created(Card $card)
    {
        if (Card::withTrashed()->count() > 0) {
            $card->number = Card::max('number') + 1;
            return;
        }
        $card->number = 1;
    }
}

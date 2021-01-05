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
        $card->number = Card::withTrashed()->count() + 1;
    }
}

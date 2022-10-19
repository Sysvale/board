<?php

namespace App\Observers;

use App\Models\Card;
use Illuminate\Support\Facades\Auth;

class CardObserver
{
	public function creating(Card $card)
	{
		$card->user_id = Auth::user()->id;
	}

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
			$card->save();
			return;
		}
		$card->number = 1;
		$card->save();
	}
}

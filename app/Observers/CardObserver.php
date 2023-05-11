<?php

namespace App\Observers;

use App\Models\Card;
use Illuminate\Support\Facades\Auth;

class CardObserver
{
	public function creating(Card $card): void
	{
		if (request()->filled('company_id')) {
			$card->company()->associate(request()->company_id);
			$card->user_id = auth()->check() ? Auth::user()->id : null;
		}

		$card->number = (Card::withTrashed()->max('number') ?: 0) + 1;
	}
}

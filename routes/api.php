<?php

use App\Http\Controllers\CardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('cards', [CardController::class, 'store'])
	->middleware('client')
	->name('api.cards.store');

Route::post('cards/batch', [CardController::class, 'handleBatch'])
	->middleware('client')
	->name('api.cards.batch');

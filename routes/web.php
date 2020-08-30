<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes([
    'reset' => false,
    'verify' => false,
]);

$any = \App\Constants\RouteConstants::ANY;
Route::redirect('/', 'login');

Route::group(
    ['middleware' => ['auth']], function () {
        Route::get('/lists/default', 'BoardListController@getDefaultLists');
        Route::get('/lists/planning', 'BoardListController@getPlanningLists');
        Route::get('/lists/devlog', 'BoardListController@getDevlogLists');

        Route::resource('cards', 'CardController')->only(['store', 'update', 'destroy']);
        Route::get(
            '/cards/impediments/{team}',
            'CardController@getImpedimentsByTeam'
        );
        Route::get('/cards/lists-ids', 'CardController@getCardsByListsIds');
        Route::post(
            '/cards/update-positions',
            'CardController@updateCardsPositions'
        );
        Route::get('/user-stories/{team}', 'CardController@getUserStoriesByTeam');
        Route::post('cards/store-many', 'CardController@storeMany');
        Route::get('/labels', 'LabelController@index');
        Route::get('/members', 'MemberController@index');
        Route::get('/teams', 'TeamController@index');
        Route::get('/boards', 'BoardController@index');

        Route::get('/logout', function () {
            Auth::logout();
            return redirect('login');
        });
    }
);

Route::get(
    "/{{$any}}", function () {
        if (Auth::check()) {
            return view('index');
        }
        return redirect('login');
    }
);

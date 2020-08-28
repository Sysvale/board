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

Route::get('/', 'HomeController@index')->name('home');

Route::get('/lists/default', 'BoardListController@getDefaultLists');
Route::get('/lists/planning', 'BoardListController@getPlanningLists');
Route::get('/lists/devlog', 'BoardListController@getDevlogLists');

Route::post('cards/store-many', 'CardController@storeMany');
Route::resource('cards', 'CardController')->only(['store', 'update', 'destroy']);
Route::get('/cards/impediments/{team}', 'CardController@getImpedimentsByTeam');
Route::get('/cards/lists-ids', 'CardController@getCardsByListsIds');
Route::post('/cards/update-positions', 'CardController@updateCardsPositions');
Route::get('/user-stories/{team}', 'CardController@getUserStoriesByTeam');

Route::resource('labels', 'LabelController')->only(['index']);
Route::resource('members', 'MemberController')->only(['index']);
Route::resource('teams', 'TeamController')->only(['index']);
Route::resource('boards', 'BoardController')->only(['index']);

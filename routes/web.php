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

Route::get('/default-lists', 'BoardListController@getDefaultLists');
Route::get('/planning-lists', 'BoardListController@getPlanningLists');

Route::resource('cards', 'CardController')->only(['store', 'update', 'destroy']);
Route::get('/get-cards-by-lists-ids', 'CardController@getCardsByListsIds');

Route::resource('labels', 'LabelController')->only(['index']);
Route::resource('members', 'MemberController')->only(['index']);
Route::resource('teams', 'TeamController')->only(['index']);

Route::middleware('auth')->group(function() {

});

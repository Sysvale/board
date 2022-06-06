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
    'verify' => false,
    'register' => false,
]);

$any = \App\Constants\RouteConstants::ANY;
Route::redirect('/', 'login');

Route::group(
    ['middleware' => ['auth']], function () {
        Route::get('/lists/default', 'BoardListController@getDefaultLists');
        Route::get('/lists/planning/{workspace}', 'BoardListController@getPlanningLists');
        Route::get('/lists/issues/', 'BoardListController@getIssuesLists');
        Route::get('/lists/devlog', 'BoardListController@getDevlogLists');

        Route::delete('cards/delete-many', 'CardController@destroyMany')
            ->name('cards.destroy_many');
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
        Route::get('/cards/synchronize', 'CardController@synchronize');
        Route::get(
            '/sprint/summary/current/{team}',
            'CardController@getCurrentSprintSummaryByTeam'
        );
        Route::get('/labels', 'LabelController@index');
        Route::resource('members', 'MemberController');
        Route::resource('/teams', 'TeamController');
        Route::get('/boards', 'BoardController@index');

        Route::resource('events', 'EventController')->only(['store', 'update', 'destroy']);
        Route::get('/events/{team}', 'EventController@getEventsByTeam');

        Route::resource('goals', 'GoalController');

        Route::get('/logout', function () {
            Auth::logout();
            return redirect('login');
        });

        Route::apiResource('workspaces', 'WorkspaceController');
        Route::apiResource('processes', 'ProcessController');

        Route::post('users/resend-welcome-mail', 'UserController@resendWelcomeMail');

        Route::get('/reports/sprint-overview/{team}', 'SprintReportController@getCurrentSprintOverviewByTeam');
    }
);

Route::get(
    "{path}", function () {
        if (Auth::check()) {
            return view('index');
        }
        return redirect('login');
    }
)->where('path', '(.*)');

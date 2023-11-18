<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
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
		Route::get('/lists/default', 'BoardListController@getTaskLists');
		Route::get('/lists/planning/company', 'BoardListController@getCompanyPlanningLists');
		Route::get('/lists/planning/{workspace}', 'BoardListController@getPlanningLists');
		Route::get('/lists/devlog', 'BoardListController@getDevlogLists');

		Route::delete('cards/delete-many', 'CardController@destroyMany')
			->name('cards.destroy_many');
		Route::resource('cards', 'CardController')->only(['store', 'update', 'destroy']);
		Route::get(
			'/cards/impediments/{team}',
			'CardController@getImpedimentsByTeam'
		);

		Route::get('/cards/from-user-story', 'CardController@getTaskCardsFromUserStory');
		Route::get('/cards/from-devlog', 'CardController@getTaskCardsFromDevlog');
		Route::get('/cards/from-not-planned', 'CardController@getTaskCardsFromNotPlanned');
		Route::get('/cards/from-kaizen', 'CardController@getTaskCardsFromKaizen');
		Route::get('/cards/from-company-planning', 'CardController@getCompanyPlanningCards');
		Route::get('/cards/from-planning', 'CardController@getPlanningCards');

		Route::post(
			'/cards/update-positions',
			'CardController@updateCardsPositions'
		);
		Route::get('/user-stories/{team}', 'CardController@getUserStoriesByTeam');
		Route::get(
			'/sprint/summary/current/{team}',
			'CardController@getCurrentSprintSummaryByTeam'
		);
		Route::resource('labels', 'LabelController');
		Route::get('/labels/workspace/{workspace}', 'LabelController@getLabelsByWorkspaceId');
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
		Route::apiResource('milestones', 'MilestoneController');
		Route::apiResource('sprint-reports', 'SprintReportController')->only(['store']);
		Route::get('sprint-reports/{team}', 'SprintReportController@getSprintReportByTeamId');


		Route::post('users/resend-welcome-mail', 'UserController@resendWelcomeMail');

		Route::get('/reports/sprint-overview/{team}', 'SprintReportController@getCurrentSprintOverviewByTeam');

		Route::resource('backlog-labels', 'BacklogLabelController');

		Route::get('milestones/{milestone}/backlog-items/not-started', 'MilestoneController@getNotStarted');
		Route::get('milestones/{milestone}/backlog-items/on-going', 'MilestoneController@getOnGoing');
		Route::get('milestones/{milestone}/backlog-items/finished', 'MilestoneController@getFinished');
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

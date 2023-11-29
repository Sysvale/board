<?php

use App\Http\Controllers\BacklogLabelController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\BoardListController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\SprintReportController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Auth;
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

Route::redirect('/', 'login');

Route::group(
	['middleware' => ['auth']], function () {
		Route::get('/lists/default', [BoardListController::class, 'getTaskLists']);
		Route::get('/lists/planning/company', [BoardListController::class, 'getCompanyPlanningLists']);
		Route::get('/lists/planning/{workspace}', [BoardListController::class, 'getPlanningLists']);
		Route::get('/lists/devlog', [BoardListController::class, 'getDevlogLists']);

		Route::delete('cards/delete-many', [CardController::class, 'destroyMany'])
			->name('cards.destroy_many');
		Route::resource('cards', CardController::class)->only(['store', 'update', 'destroy']);
		Route::get('/cards/impediments/{team}', [CardController::class, 'getImpedimentsByTeam']);
		Route::get('/cards/from-user-story', [CardController::class, 'getTaskCardsFromUserStory']);
		Route::get('/cards/from-devlog', [CardController::class, 'getTaskCardsFromDevlog']);
		Route::get('/cards/from-not-planned', [CardController::class, 'getTaskCardsFromNotPlanned']);
		Route::get('/cards/from-kaizen', [CardController::class, 'getTaskCardsFromKaizen']);
		Route::get('/cards/from-company-planning', [CardController::class, 'getCompanyPlanningCards']);
		Route::get('/cards/from-planning', [CardController::class, 'getPlanningCards']);
		Route::post( '/cards/update-positions', [CardController::class, 'updateCardsPositions']);

		Route::get('/user-stories/{team}', [CardController::class, 'getUserStoriesByTeam']);

        Route::get( '/sprint/summary/current/{team}', [CardController::class, 'getCurrentSprintSummaryByTeam']);

        Route::resource('labels', LabelController::class);
		Route::get('/labels/workspace/{workspace}', [LabelController::class, 'getLabelsByWorkspaceId']);

		Route::resource('members', MemberController::class);
		Route::resource('/teams', TeamController::class);
		Route::get('/boards', [BoardController::class, 'index']);

		Route::resource('events', EventController::class)->only(['store', 'update', 'destroy']);
		Route::get('/events/{team}', [EventController::class, 'getEventsByTeam']);

		Route::resource('goals', GoalController::class);

		Route::get('/logout', function () {
			Auth::logout();
			return redirect('login');
		});

		Route::apiResource('workspaces', WorkspaceController::class);
		Route::apiResource('processes', ProcessController::class);
		Route::apiResource('milestones', MilestoneController::class);
		Route::apiResource('sprint-reports', SprintReportController::class)->only(['store']);
		Route::get('sprint-reports/{team}', [SprintReportController::class, 'getSprintReportByTeamId']);


		Route::post('users/resend-welcome-mail', [UserController::class, 'resendWelcomeMail']);

		Route::get('/reports/sprint-overview/{team}', [SprintReportController::class, 'getCurrentSprintOverviewByTeam']);

		Route::resource('backlog-labels', BacklogLabelController::class);

		Route::get('milestones/{milestone}/backlog-items/not-started', [MilestoneController::class, 'getNotStarted']);
		Route::get('milestones/{milestone}/backlog-items/on-going', [MilestoneController::class, 'getOnGoing']);
		Route::get('milestones/{milestone}/backlog-items/finished', [MilestoneController::class, 'getFinished']);
	}
);

Route::get(
	"/v2/{path}", function () {
		if (Auth::check()) {
			return view('index-vue-3');
		}
		return redirect('login');
	}
)->where('path', '(.*)');

Route::get(
	"{path}", function () {
		if (Auth::check()) {
			return view('index-vue-2');
		}
		return redirect('login');
	}
)->where('path', '(.*)');

<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Http\Resources\TeamResource;

class TeamController extends Controller
{
	public function index()
	{
		return TeamResource::collection(Team::get());
	}
}

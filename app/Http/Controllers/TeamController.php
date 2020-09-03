<?php

namespace App\Http\Controllers;

use App\Models\Team;

class TeamController extends Controller
{
	public function index()
	{
		return Team::get();
	}
}

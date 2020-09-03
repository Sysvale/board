<?php

namespace App\Http\Controllers;

use App\Models\Board;

class BoardController extends Controller
{
	public function index()
	{
		return Board::get();
	}
}

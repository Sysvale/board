<?php

namespace App\Http\Controllers;

use App\Models\Label;

class LabelController extends Controller
{
	public function index()
	{
		return Label::get();
	}
}

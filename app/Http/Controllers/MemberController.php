<?php

namespace App\Http\Controllers;

use App\Models\Member;

class MemberController extends Controller
{
	public function index()
	{
		return Member::get();
	}
}

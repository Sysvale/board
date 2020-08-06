<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
  public function index(Request $in)
  {
		return view('app/example');
		// if (!Auth::check()) {
		// 	return redirect()->route('login');
		// }

		// switch (Auth::user()->type) {
		// 	case 'C':
		// 		return view('app/teste');

		// 	default:
		// 		return 'Sem permissão';
		// 		break;
		// }
  }
}

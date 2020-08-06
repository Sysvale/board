<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\User;
use Auth;

class AuthController extends Controller
{
    public function __construct()
    {
      $this->middleware('guest')->except('logout');
		}

		public function index(Request $in)
		{
			if (Auth::check()) {
				return redirect('/');
			}

			return view('app.auth');
		}

		public function login(Request $in) {
			$user = User::where('email', $in->email)->first();

			if (!$user) {
				abort(401, 'Credenciais inválidas. Verifique seu email ou senha');
			}

			if (!$user->active || $user->account->status == Account::STATUS_DISABLED) {
				abort(401, 'Sem permissões para acessar.');
			}

			if (!Auth::attempt(['email' => $in->email, 'password' => $in->password, 'active' => true])) {
				abort(401, 'Usuário ou senha inválidos');
			}

			return response(200);
		}

		public function logout() {
			Auth::logout();

			return redirect('/');
		}
}

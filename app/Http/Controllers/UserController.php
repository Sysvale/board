<?php

namespace App\Http\Controllers;

use App\User;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
	public function resendWelcomeMail(Request $request)
	{
		$request->validate([
			'user_id' => 'required|string',
		]);

		$user = User::findOrFail($request->user_id);
		Mail::to($user->email)->send(new WelcomeMail($user));

		return Response::json('E-mail enviado com sucesso!');
	}
}

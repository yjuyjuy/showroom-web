<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;

class LoginController extends Controller
{
	public function handle(Request $request)
	{
		$credentials = $request->validate([
			'email' => 'required|string',
			'password' => 'required|string',
		]);
		if (!Auth::attempt($credentials)) {
			return [ 'failed' => true, ];
		}
		$user = Auth::user();
		$token = $user->createToken('My Token')->accessToken;
		return [
			'user' => Auth::user(),
			'token' => $token,
		];
	}
}

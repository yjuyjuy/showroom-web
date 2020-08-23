<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{

	public function edit()
	{
		$user = auth()->user();
		return view('account.edit', compact('user'));
	}

	public function update(Request $request)
	{
		$user = auth()->user();
		if ($request->input('username') != $user->username) {
			$user->username = $request->validate([
				'username' => ['required', 'string', 'max:255', 'unique:users'],
			])['username'];
		}
		if ($request->input('email') != $user->email) {
			$user->email = $request->validate([
				'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			])['email'];
		}
		$user->save();
		return ['success'];
	}
}

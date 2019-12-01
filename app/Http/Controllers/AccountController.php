<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
	public function status()
	{
		$user = auth()->user();
		return view('account.status', compact('user'));
	}

	public function request(Request $request)
	{
		$user = auth()->user();
		if (!$user->type) {
			$wechat_id = $request->validate([
				'wechat_id' => ['required', 'string', 'max:255', 'unique:users'],
			])['wechat_id'];
			$user->wechat_id = $wechat_id;
			$user->is_pending = true;
			$user->save();
		}
		return redirect(route('account.status'));
	}

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
		if ($request->input('wechat_id') != $user->wechat_id) {
			$user->wechat_id = $request->validate([
				'wechat_id' => ['nullable', 'string', 'max:255', 'unique:users'],
			])['wechat_id'];
		}
		$user->save();
		return ['success'];
	}
}

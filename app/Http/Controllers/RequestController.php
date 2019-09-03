<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RequestController extends Controller
{
	public function index()
	{
		$users = \App\User::where('type', 'pending')->orderBy('updated_at')->get();
		return view('admin.requests', compact('users'));
	}
	
	public function agree(Request $request)
	{
		$user = \App\User::find($request->validate(['user_id' => 'exists:users,id'])['user_id']);
		$user->is_reseller = true;
		$user->save();
		return ['success'];
	}

	public function reject(Request $request)
	{
		$user = \App\User::find($request->validate(['user_id' => 'exists:users,id'])['user_id']);
		$user->is_rejected = true;
		$user->save();
		return ['success'];
	}
}

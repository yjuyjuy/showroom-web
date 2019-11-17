<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class RequestController extends Controller
{
	public function index()
	{
		$users = \App\User::where('type', 'pending')->orderBy('updated_at')->get();
		return view('admin.requests', compact('users'));
	}

	public function agree(Request $request, User $user)
	{
		$user->is_reseller = true;
		$user->save();
		return ['success'];
	}

	public function reject(Request $request, User $user)
	{
		$user->is_rejected = true;
		$user->save();
		return ['success'];
	}
}

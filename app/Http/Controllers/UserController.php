<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
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

	public function following_vendors(Request $request)
	{
		$user = auth()->user();
		$message = false;

		if ($request->input('wechat_id')) {
			$wechat_id = $request->validate([
				'wechat_id' => ['sometimes', 'string', 'max:255'],
			])['wechat_id'];
			$vendor = \App\Vendor::where('wechat_id', $wechat_id)->first();
			if (!$vendor) {
				$message = '没有找到微信号是"'.$wechat_id.'"的同行';
			} elseif ($user->following_vendors->contains($vendor)) {
				$message = '已经关注"'.$vendor->name.'"了';
			} else {
				$user->following_vendors()->attach($vendor);
				$message = '成功添加关注"'.$vendor->name.'"';
			}
		}
		$vendors = $user->fresh()->following_vendors;
		return view('vendors.following', compact('vendors', 'message'));
	}
	public function edit()
	{
		$user = auth()->user();
		return view('account.settings.edit', compact('user'));
	}
}

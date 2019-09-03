<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FollowVendorController extends Controller
{
	public function unfollow(Vendor $vendor)
	{
		$user = auth()->user();
		return $user->following_vendors()->detach($vendor);
	}
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FollowRetailerController extends Controller
{
	public function follow(Retailer $retailer)
	{
		$user = auth()->user();
		return $user->following_retailers()->syncWithoutDetaching($retailer);
	}

	public function unfollow(Retailer $retailer)
	{
		$user = auth()->user();
		return $user->following_retailers()->detach($retailer);
	}
}

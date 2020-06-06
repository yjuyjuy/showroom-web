<?php

namespace App\Http\Controllers\api\v3\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Retailer;

class RetailerController extends Controller
{
	public function index()
	{
		return auth()->user()->following_retailers()->with('image')->get();
	}
	public function show(Retailer $retailer)
	{
		return $retailer;
	}
	public function follow(Retailer $retailer)
	{
		auth()->user()->following_retailers()->syncWithoutDetaching($retailer);
		return $this->following();
	}
	public function unfollow(Retailer $retailer)
	{
		auth()->user()->following_retailers()->detach($retailer);
		return $this->following();
	}
}

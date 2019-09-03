<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class FollowProductController extends Controller
{
	public function follow(Product $product)
	{
		$user = auth()->user();
		if (!$user->following_products->contains($product)) {
			$user->following_products()->attach($product);
		}
		return ['success'];
	}

	public function unfollow(Product $product)
	{
		$user = auth()->user();
		if ($user->following_products->contains($product)) {
			$user->following_products()->detach($product);
		}
		return ['success'];
	}
}

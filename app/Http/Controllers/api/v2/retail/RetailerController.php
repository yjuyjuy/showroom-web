<?php

namespace App\Http\Controllers\api\v2\retail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Retailer;

class RetailerController extends Controller
{
		public function index() {
			return [
				'retailers' => Retailer::all(),
			];
		}
		public function follow($retailerId) {
			auth()->user()->following_retailers()->syncWithoutDetaching($retailerId);
			return $this->following();
		}
		public function unfollow($retailerId) {
			auth()->user()->following_retailers()->detach($retailerId);
			return $this->following();
		}
		public function following() {
			return [
				'following_retailers' => auth()->user()->following_retailers()->pluck('retailer_id'),
			];
		}
}

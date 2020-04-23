<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Vendor;

class VendorController extends Controller
{
		public function index() {
			return [
				'vendors' => Vendor::all(),
			];
		}
		public function follow(Vendor $vendor) {
			auth()->user()->following_vendors()->syncWithoutDetaching($vendor);
			return $this->following();
		}
		public function unfollow(Vendor $vendor) {
			auth()->user()->following_vendors()->detach($vendor);
			return $this->following();
		}
		public function following() {
			return [
				'following_vendors' => auth()->user()->following_vendors()->pluck('id'),
			];
		}
}

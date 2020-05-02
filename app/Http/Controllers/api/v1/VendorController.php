<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Vendor;

class VendorController extends Controller
{
		public function index() {
			return [
				'vendors' => Vendor::with('image')->get(),
			];
		}
		public function follow($vendorId) {
			auth()->user()->following_vendors()->syncWithoutDetaching($vendorId);
			return $this->following();
		}
		public function unfollow($vendorId) {
			auth()->user()->following_vendors()->detach($vendorId);
			return $this->following();
		}
		public function following() {
			return [
				'following_vendors' => auth()->user()->following_vendors()->pluck('vendor_id'),
			];
		}
}

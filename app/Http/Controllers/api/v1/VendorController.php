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
}

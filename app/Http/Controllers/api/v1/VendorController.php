<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VendorController extends Controller
{
		public function index() {
			return [
				'vendors' => Vendor::all(),
			];
		}
}

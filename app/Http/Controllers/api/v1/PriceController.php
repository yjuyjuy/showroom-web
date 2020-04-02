<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Vendor;
use App\VendorPrice;
use Illuminate\Support\Facades\Cache;

class PriceController extends Controller
{
	public function index(Request $request) {
		return Cache::remember($request->fullUrl(), 1 * 60, function() use ($request) {
			if ($request->query('vendor')) {
				$query = Vendor::findOrFail($request->query('vendor'))->prices();
			} else {
				$query = VendorPrice::query();
			}
			$query->orderBy('updated_at', 'desc');
			$total_pages = ceil($query->count() / 24.0);
			$page = min(max(request()->query('page', 1), 1), $total_pages);
			$prices = $query->forPage($page, 24)->get();
			$prices->load(['vendor', 'product', 'product.brand', 'product.season', 'product.images', 'product.offers']);
			return [
				'page' => $page,
				'total_pages' => $total_pages,
				'prices' => $prices->values(),
			];
		});
	}
}

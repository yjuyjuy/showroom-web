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
			$filters = $this->validateFilters();
			foreach ($filters as $field => $values) {
				$query->whereHas('product', function($query) use ($field, $values) {
					$query->whereIn("{$field}_id", $values);
				});
			}
			$query->orderBy('updated_at', 'desc');
			$total_pages = ceil($query->count() / 24.0);
			$page = min(max(request()->query('page', 1), 1), $total_pages);
			$prices = $query->forPage($page, 24)->get();
			$prices->loadMissing(['vendor', 'product', 'product.brand', 'product.season', 'product.images', 'product.offers']);
			return [
				'page' => $page,
				'total_pages' => $total_pages,
				'prices' => $prices->values(),
				'filter_options' => $this->filterOptions(),
			];
		});
	}

	public function validateFilters() {
		return (new \App\Http\Controllers\ProductController())->validateFilters();
	}

	public function store(VendorPrice $price) {
		return (new \App\Http\Controllers\PriceController())->store($price);
	}

	public function update(VendorPrice $price) {
		return (new \App\Http\Controllers\PriceController())->update($price);
	}

	public function destroy(VendorPrice $price) {
		return (new \App\Http\Controllers\PriceController())->destroy($price);
	}

	public function filterOptions($value='')
	{
		return (new \App\Http\Controllers\ProductController())->filterOptions();
	}
}

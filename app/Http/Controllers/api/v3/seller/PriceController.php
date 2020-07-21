<?php

namespace App\Http\Controllers\api\v3\seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Log;
use App\Vendor;
use App\VendorPrice;
use App\Product;
use Illuminate\Support\Facades\Cache;

class PriceController extends Controller
{
	public function index(Request $request)
	{
		// return Cache::remember($request->fullUrl(), 1 * 60, function() use ($request) {
		if ($request->query('vendor') && $vendor = Vendor::where('name', $request->query('vendor'))->first()) {
			$query = $vendor->prices();
		} else {
			$query = VendorPrice::whereIn('vendor_id', auth()->user()->following_vendors()->pluck('vendor_id'));
		}
		$filters = $this->validateFilters();
		foreach ($filters as $field => $values) {
			$query->whereHas('product', function ($query) use ($field, $values) {
				$query->whereIn("{$field}_id", $values);
			});
		}
		$query->orderBy('updated_at', 'desc');
		$total_pages = ceil($query->count() / 24.0);
		$page = min(max(request()->query('page', 1), 1), $total_pages);
		$prices = $query->forPage($page, 24)->get();
		$prices->loadMissing(['vendor', 'vendor.image', 'product', 'product.brand', 'product.season', 'product.images', 'product.offers']);
		return [
			'page' => $page,
			'total_pages' => $total_pages,
			'prices' => $prices->values(),
			'filter_options' => $this->filterOptions(),
		];
		// });
	}

	public function validateFilters()
	{
		return (new \App\Http\Controllers\ProductController())->validateFilters();
	}

	public function store(Product $product)
	{
		if (auth()->user()->is_admin) {
			$vendor = Vendor::find(request()->input('vendor'));
		} else {
			$vendor = auth()->user()->vendor;
		}
		$data = json_decode($this->validateRequest()['data'], true);
		if (!$data) {
			return ['error' => 'Empty data'];
		} else if (!$vendor) {
			return ['error' => 'Seller not found'];
		} else {
			$price = $product->prices()->firstOrNew(['vendor_id' => $vendor->id]);
			$price->data = $data;
			$price->updated_at = NOW();
			$price->save();
			if ($product->updated_at < NOW()->subday(1)) {
				$product->touch();
			}
			Log::create([
				'content' => auth()->user()->username . '新增了' . $price->vendor->name . '的' . $product->displayName() . '的价格',
				'url' => route('products.show', ['product' => $product]),
			]);
			return ['price' => $price->load('vendor')];
		}
	}

	public function update(VendorPrice $price)
	{
		(new \App\Http\Controllers\PriceController())->update($price);
		return ['price' => $price->load('vendor')];
	}

	public function destroy(VendorPrice $price)
	{
		return (new \App\Http\Controllers\PriceController())->destroy($price);
	}

	public function filterOptions()
	{
		return (new \App\Http\Controllers\ProductController())->filterOptions();
	}

	public function validateRequest()
	{
		return (new \App\Http\Controllers\PriceController())->validateRequest();
	}
}

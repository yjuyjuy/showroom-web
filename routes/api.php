<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Product;
use App\Vendor;
use App\VendorPrice;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});

Route::get('/products', function() {
	$request = request();
	return Cache::remember(request()->fullUrl(), 1 * 60, function() use ($request) {
		if ($request->query('vendor')) {
			$query = Vendor::findOrFail($request->query('vendor'))->products();
		} else {
			$query = Product::query();
		}
		$query->orderBy('updated_at', 'desc');
		$total_pages = ceil($query->count() / 24.0);
		$page = min(max(request()->query('page', 1), 1), $total_pages);
		$products = $query->forPage($page, 24)->get();
		$products->load(['brand', 'images', 'season', 'offers', 'offers.vendor']);
		return [
			'page' => $page,
			'total_pages' => $total_pages,
			'user' => auth()->user(),
			'products' => $products->values(),
		];
	});
});
Route::get('/products/{product}', function(Product $product) {
	return $product->load([
		'brand', 'season', 'color', 'category', 'prices', 'prices.vendor', 'offers', 'offers.vendor',
		'images' => function($query) {
			$query->orderBy('order', 'asc');
		},
	]);
});
Route::get('/prices', function() {
	$request = request();
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
});
Route::get('/vendors', function() {
	return [
		'vendors' => Vendor::all(),
	];
});

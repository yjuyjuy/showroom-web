<?php

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Cache;
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
	return Cache::remember('api-products-page'.request()->query('page', 1), 5 * 60, function() {
		$query = \App\Product::orderBy('updated_at', 'desc');
		$total_pages = ceil($query->count() / 24.0);
		$page = min(max(request()->query('page', 1), 1), $total_pages);
		$products = $query->forPage($page, 24)->get();
		$products->load(['brand', 'images', 'season', 'retails', 'retails.retailer', 'offers', 'offers.vendor']);
		return json_encode([
			'page' => $page,
			'total_pages' => $total_pages,
			'user' => auth()->user(),
			'products' => $products->values(),
		], JSON_FORCE_OBJECT);
	});
});
Route::get('/products/{product}', function(\App\Product $product) {
	return $product->load([
		'brand', 'season', 'color', 'category', 'prices', 'prices.vendor', 'retails', 'retails.retailer', 'offers', 'offers.vendor',
		'images' => function($query) {
			$query->orderBy('order', 'asc');
		},
	]);
});
Route::get('/posts', function() {
	return Cache::remember('posts-page'.request()->query('page', 1), 2 * 60, function() {
		$query = \App\VendorPrice::orderBy('updated_at', 'desc');
		$total_pages = ceil($query->count() / 24.0);
		$page = min(max(request()->query('page', 1), 1), $total_pages);
		$prices = $query->forPage($page, 24)->get();
		$prices->load(['vendor', 'product', 'product.brand', 'product.season', 'product.images', 'product.retails', 'product.offers']);
		return json_encode([
			'page' => $page,
			'total_pages' => $total_pages,
			'prices' => $prices->values(),
		], JSON_FORCE_OBJECT);
	});
});

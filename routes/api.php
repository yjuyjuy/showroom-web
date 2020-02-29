<?php

use Illuminate\Http\Request;

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
	return \Illuminate\Support\Facades\Cache::remember('api-products-page'.request()->query('page', 1), 5 * 60, function() {
		$products = \App\Product::orderBy('updated_at', 'desc')->get();
		$total_pages = ceil($products->count() / 24.0);
		$page = min(max(request()->query('page', 1), 1), $total_pages);
		$products = $products->forPage($page, 24);
		$products->load(['brand', 'images', 'season', 'retails', 'retails.retailer', 'offers', 'offers.vendor']);
		return [
			'page' => $page,
			'total_pages' => $total_pages,
			'user' => auth()->user(),
			'products' => $products->values(),
		];
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

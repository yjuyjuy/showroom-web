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
	$products = \App\Product::orderBy('updated_at', 'desc')->get();
	$total_pages = ceil($products->count() / 48.0);
	$page = min(max(request()->query('page', 1), 1), $total_pages);
	$products = $products->forPage($page, 48);
	$products->load(['brand', 'images']);
	return [
		'page' => $page,
		'total_pages' => $total_pages,
		'user' => auth()->user(),
		'products' => $products->values(),
	];
});
Route::get('/products/{product}', function(\App\Product $product) {
	return $product->load([
		'brand', 'season', 'color', 'category', 'prices', 'prices.vendor',
		'images' => function($query) {
			$query->orderBy('order', 'asc');
		},
	]);
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::post('/register', 'api\v1\RegisterController@handle');

Route::middleware('auth:api')->group(function() {
	Route::get('/products', 'api\v1\ProductController@index');
	Route::get('/products/{product}', 'api\v1\ProductController@show');
	Route::get('/prices', 'api\v1\PriceController@index');
	Route::get('/vendors', 'api\v1\VendorController@index');

	Route::get('/user', function() {
		return [
			'user' => auth()->user(),
			'token' => auth()->user()->token(),
		];
	});
	Route::get('/options', function() {
		return [
			'sort_options' => (new App\Http\Controllers\ProductController())->sortOptions(),
			'filter_options' => (new App\Http\Controllers\ProductController())->filterOptions(),
		];
	});
});

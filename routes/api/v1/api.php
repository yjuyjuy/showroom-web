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

Route::post('register', 'api\v1\RegisterController@handle');

Route::middleware(['auth:api', 'vendor'])->group(function() {
	Route::get('user', 'api\v1\UserController@show');
	Route::post('user', 'api\v1\UserController@update');

	Route::get('products', 'api\v1\ProductController@index');
	Route::get('products/following', 'api\v1\ProductController@following');
	Route::get('products/{product}', 'api\v1\ProductController@show');
	Route::post('products/{product}/follow', 'api\v1\ProductController@follow');
	Route::post('products/{product}/unfollow', 'api\v1\ProductController@unfollow');

	Route::get('prices', 'api\v1\PriceController@index');
	Route::post('/products/{product}/prices', 'api\v1\PriceController@store');
	Route::patch('prices/{price}', 'api\v1\PriceController@update');
	Route::delete('prices/{price}', 'api\v1\PriceController@destroy');
	Route::post('prices/{price}/+/{size}', 'PriceController@add');
	Route::post('prices/{price}/-/{size}', 'PriceController@subtract');

	Route::post('products/{product}/measurement', 'MeasurementController@store');
	Route::patch('products/{product}/measurement', 'MeasurementController@update');
	Route::delete('products/{product}/measurement', 'MeasurementController@destroy');

	Route::get('vendors', 'api\v1\VendorController@index');
	Route::post('vendor', 'api\v1\VendorController@update');
	Route::post('vendors/{vendor}/follow', 'api\v1\VendorController@follow');
	Route::post('vendors/{vendor}/unfollow', 'api\v1\VendorController@unfollow');
});

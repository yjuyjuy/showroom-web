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
	Route::get('user', function() {
		return [
			'user' => auth()->user(),
			'token' => auth()->user()->token(),
		];
	});

	Route::get('products', 'api\v1\ProductController@index');
	Route::get('products/{product}', 'api\v1\ProductController@show');
	Route::post('products/{product}/follow', 'ProductController@follow');
	Route::post('products/{product}/unfollow', 'ProductController@unfollow');

	Route::get('prices', 'api\v1\PriceController@index');
	Route::post('prices', 'api\v1\PriceController@store');
	Route::patch('prices/{price}', 'api\v1\PriceController@update');
	Route::delete('prices/{price}', 'api\v1\PriceController@destroy');
	Route::post('prices/{price}/+/{size}', 'PriceController@add');
	Route::post('prices/{price}/-/{size}', 'PriceController@subtract');

	Route::post('products/{product}/measurement', 'MeasurementController@store');
	Route::patch('products/{product}/measurement', 'MeasurementController@update');
	Route::delete('products/{product}/measurement', 'MeasurementController@destroy');

	Route::get('vendors', 'api\v1\VendorController@index');
});

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

Route::get('version', function () {
	return [
		'min' => '0.3.1',
		'current' => '0.3.1',
	];
});

Route::post('register', 'api\v3\seller\RegisterController@register');

Route::middleware(['auth:api', 'reseller:api'])->group(function () {
	Route::get('user', 'api\v3\seller\UserController@show');
	Route::post('user', 'api\v3\seller\UserController@update');

	Route::post('user/address', 'api\v3\seller\AddressController@store');
	Route::patch('user/address/{address}', 'api\v3\seller\AddressController@update');
	Route::delete('user/address/{address}', 'api\v3\seller\AddressController@destroy');

	Route::post('devices', 'api\v3\seller\DeviceController@store');

	Route::get('products', 'api\v3\seller\ProductController@index');
	Route::get('products/following', 'api\v3\seller\ProductController@following');
	Route::get('products/{product}', 'api\v3\seller\ProductController@show');
	Route::post('products/{product}/follow', 'api\v3\seller\ProductController@follow');
	Route::post('products/{product}/unfollow', 'api\v3\seller\ProductController@unfollow');

	Route::get('orders', 'api\v3\seller\OrderController@index');
	Route::post('orders', 'api\v3\seller\OrderController@store');
	Route::get('orders/{order}', 'api\v3\seller\OrderController@show');
	Route::patch('orders/{order}/confirm', 'api\v3\seller\OrderController@confirm');
	Route::patch('orders/{order}/decline', 'api\v3\seller\OrderController@decline');
	Route::patch('orders/{order}/receivePayment', 'api\v3\seller\OrderController@receivePayment');
	Route::patch('orders/{order}/ship', 'api\v3\seller\OrderController@ship');
	Route::patch('orders/{order}/deliver', 'api\v3\seller\OrderController@deliver');
	Route::patch('orders/{order}/complete', 'api\v3\seller\OrderController@complete');
	Route::patch('orders/{order}/cancel', 'api\v3\seller\OrderController@cancel');

	Route::get('prices', 'api\v3\seller\PriceController@index');
	Route::post('/products/{product}/prices', 'api\v3\seller\PriceController@store');
	Route::patch('prices/{price}', 'api\v3\seller\PriceController@update');
	Route::delete('prices/{price}', 'api\v3\seller\PriceController@destroy');
	Route::post('prices/{price}/+/{size}', 'PriceController@add');
	Route::post('prices/{price}/-/{size}', 'PriceController@subtract');

	Route::post('products/{product}/measurement', 'api\v3\seller\MeasurementController@store');
	Route::patch('products/{product}/measurement', 'api\v3\seller\MeasurementController@update');
	Route::delete('products/{product}/measurement', 'api\v3\seller\MeasurementController@destroy');

	Route::get('vendors', 'api\v3\seller\VendorController@index');
	Route::post('vendor', 'api\v3\seller\VendorController@update');
	Route::post('vendors/{vendor}/follow', 'api\v3\seller\VendorController@follow');
	Route::post('vendors/{vendor}/unfollow', 'api\v3\seller\VendorController@unfollow');

	Route::post('retailer', 'api\v3\seller\RetailerController@update');
});

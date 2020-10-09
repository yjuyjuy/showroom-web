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

Route::get('version', 'api\v3\seller\VersionController');

Route::post('register', 'api\v3\seller\RegisterController@register');
Route::get('forgot_password', 'api\v3\shared\AuthController@forgot');
Route::post('forgot_password', 'api\v3\shared\AuthController@verify');
Route::patch('forgot_password', 'api\v3\shared\AuthController@reset');

Route::middleware(['auth:api'])->group(function () {
	// User model
	Route::get('user', 'api\v3\seller\UserController@show');
	Route::post('user', 'api\v3\seller\UserController@update');

	// Address model
	Route::post('user/address', 'api\v3\seller\AddressController@store');
	Route::patch('user/address/{address}', 'api\v3\seller\AddressController@update');
	Route::delete('user/address/{address}', 'api\v3\seller\AddressController@destroy');

	// Device model
	Route::post('devices', 'api\v3\seller\DeviceController@store');
	Route::delete('devices/{token}', 'api\v3\seller\DeviceController@destroy');

	// Product model
	Route::get('products', 'api\v3\seller\ProductController@index');
	Route::get('products/following', 'api\v3\seller\ProductController@following');
	Route::get('products/{product}', 'api\v3\seller\ProductController@show');
	Route::post('products/{product}/follow', 'api\v3\seller\ProductController@follow');
	Route::post('products/{product}/unfollow', 'api\v3\seller\ProductController@unfollow');
	Route::get('products/{product}/similar', 'api\v3\shared\ProductController@similar');
	
	Route::post('images', 'api\v3\seller\ImageController@store')->middleware('admin');

	Route::get('brands', 'api\v3\shared\BrandController@index');
	Route::get('categories', 'api\v3\shared\CategoryController@index');

	// Order model
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

	// Price model
	Route::get('prices', 'api\v3\seller\PriceController@index');
	Route::post('/products/{product}/prices', 'api\v3\seller\PriceController@store');
	Route::patch('prices/{price}', 'api\v3\seller\PriceController@update');
	Route::delete('prices/{price}', 'api\v3\seller\PriceController@destroy');
	Route::post('prices/{price}/+/{size}', 'PriceController@add');
	Route::post('prices/{price}/-/{size}', 'PriceController@subtract');

	// Measurement model
	Route::post('products/{product}/measurement', 'api\v3\seller\MeasurementController@store');
	Route::patch('products/{product}/measurement', 'api\v3\seller\MeasurementController@update');
	Route::delete('products/{product}/measurement', 'api\v3\seller\MeasurementController@destroy');

	// SourceProduct
	Route::get('farfetch/{product}', 'api\v3\shared\SourceProductController@farfetch');
	Route::get('end/{product}', 'api\v3\shared\SourceProductController@end');
	Route::get('dior/{product}', 'api\v3\shared\SourceProductController@dior');
	Route::get('gucci/{product}', 'api\v3\shared\SourceProductController@gucci');
	Route::get('off-white/{product}', 'api\v3\shared\SourceProductController@offwhite');
	Route::get('balenciaga/{product}', 'api\v3\shared\SourceProductController@balenciaga');
	Route::get('ssense/{product}', 'api\v3\shared\SourceProductController@ssense');

	// Vendor model
	Route::get('vendors', 'api\v3\seller\VendorController@index');
	Route::post('vendors', 'api\v3\seller\VendorController@update');
	Route::get('vendors/{vendor}', 'api\v3\seller\VendorController@show');
	Route::post('vendors/{vendor}/follow', 'api\v3\seller\VendorController@follow');
	Route::post('vendors/{vendor}/unfollow', 'api\v3\seller\VendorController@unfollow');

	// Message model
	Route::get('messages/pull', 'api\v3\seller\MessageController@pull');
	Route::post('messages/push', 'api\v3\seller\MessageController@push');
	Route::get('contacts', 'api\v3\seller\ContactController@index');

	Route::get('channels', 'api\v3\seller\ChannelController@index');
});

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

Route::get('version', 'api\v3\shared\VersionController');
Route::get('ip', 'api\v3\shared\HelperController@ip');
Route::get('geolocate', 'api\v3\shared\HelperController@geolocate');
Route::get('server', 'api\v3\shared\HelperController@server');

Route::post('register', 'api\v3\customer\RegisterController@register');
Route::get('forgot_password', 'api\v3\shared\AuthController@forgot');
Route::post('forgot_password', 'api\v3\shared\AuthController@verify');
Route::patch('forgot_password', 'api\v3\shared\AuthController@reset');

Route::middleware(['auth:api'])->group(function () {
	// User model
	Route::get('user', 'api\v3\customer\UserController@show');
	Route::post('user', 'api\v3\customer\UserController@update');

	// Address model
	Route::post('user/address', 'api\v3\seller\AddressController@store');
	Route::patch('user/address/{address}', 'api\v3\seller\AddressController@update');
	Route::delete('user/address/{address}', 'api\v3\seller\AddressController@destroy');

	// Device model
	Route::post('devices', 'api\v3\customer\DeviceController@store');
	Route::delete('devices/{token}', 'api\v3\customer\DeviceController@destroy');

	// Product model
	Route::get('products', 'api\v3\customer\ProductController@index');
	Route::get('products/following', 'api\v3\customer\ProductController@following');
	Route::get('products/{product}', 'api\v3\customer\ProductController@show');
	Route::post('products/{product}/follow', 'api\v3\customer\ProductController@follow');
	Route::post('products/{product}/unfollow', 'api\v3\customer\ProductController@unfollow');
	Route::get('products/{product}/similar', 'api\v3\shared\ProductController@similar');

	Route::get('brands', 'api\v3\shared\BrandController@index');
	Route::get('categories', 'api\v3\shared\CategoryController@index');

	// Order model
	Route::get('orders', 'api\v3\customer\OrderController@index');
	Route::post('orders', 'api\v3\customer\OrderController@store');
	Route::get('orders/{order}', 'api\v3\customer\OrderController@show');
	Route::patch('orders/{order}/deliver', 'api\v3\customer\OrderController@deliver');
	Route::patch('orders/{order}/complete', 'api\v3\customer\OrderController@complete');
	Route::patch('orders/{order}/cancel', 'api\v3\customer\OrderController@cancel');

	// RetailPrice model
	Route::get('prices', 'api\v3\customer\RetailController@index');

	// SourceProduct
	Route::get('farfetch/{product}', 'api\v3\shared\SourceProductController@farfetch');
	Route::get('end/{product}', 'api\v3\shared\SourceProductController@end');
	Route::get('dior/{product}', 'api\v3\shared\SourceProductController@dior');
	Route::get('gucci/{product}', 'api\v3\shared\SourceProductController@gucci');
	Route::get('off-white/{product}', 'api\v3\shared\SourceProductController@offwhite');
	Route::get('balenciaga/{product}', 'api\v3\shared\SourceProductController@balenciaga');
	Route::get('ssense/{product}', 'api\v3\shared\SourceProductController@ssense');

	// Retailer model
	Route::get('retailers', 'api\v3\customer\RetailerController@index');
	Route::get('retailers/{retailer}', 'api\v3\customer\RetailerController@show');
	Route::post('retailers/{retailer}/follow', 'api\v3\customer\RetailerController@follow');
	Route::post('retailers/{retailer}/unfollow', 'api\v3\customer\RetailerController@unfollow');

	// Message model
	Route::get('messages/pull', 'api\v3\customer\MessageController@pull');
	Route::post('messages/push', 'api\v3\customer\MessageController@push');
	Route::get('contacts', 'api\v3\customer\ContactController@index');

	Route::get('channels', 'api\v3\customer\ChannelController@index');
});

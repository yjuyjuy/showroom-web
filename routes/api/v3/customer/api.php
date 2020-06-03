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

Route::post('register', 'api\v3\customer\RegisterController@register');

Route::middleware(['auth:api'])->group(function() {
	Route::get('user', 'api\v3\customer\UserController@show');
	Route::post('user', 'api\v3\customer\UserController@update');

	Route::post('devices', 'api\v3\customer\DeviceController@store');
	
	Route::get('products', 'api\v3\customer\ProductController@index');
	Route::get('products/following', 'api\v3\customer\ProductController@following');
	Route::get('products/{product}', 'api\v3\customer\ProductController@show');
	Route::post('products/{product}/follow', 'api\v3\customer\ProductController@follow');
	Route::post('products/{product}/unfollow', 'api\v3\customer\ProductController@unfollow');

	Route::get('prices', 'api\v3\customer\RetailController@index');

	Route::get('retailers', 'api\v3\customer\RetailerController@index');
	Route::post('retailers/{retailer}/follow', 'api\v3\customer\RetailerController@follow');
	Route::post('retailers/{retailer}/unfollow', 'api\v3\customer\RetailerController@unfollow');
});

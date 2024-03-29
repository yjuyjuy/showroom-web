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
Route::post('register', 'api\v2\customer\RegisterController@register');

Route::middleware(['auth:api'])->group(function() {
	Route::get('user', 'api\v2\customer\UserController@show');
	Route::post('user', 'api\v2\customer\UserController@update');

	Route::get('products', 'api\v2\customer\ProductController@index');
	Route::get('products/following', 'api\v2\customer\ProductController@following');
	Route::get('products/{product}', 'api\v2\customer\ProductController@show');
	Route::post('products/{product}/follow', 'api\v2\customer\ProductController@follow');
	Route::post('products/{product}/unfollow', 'api\v2\customer\ProductController@unfollow');

	Route::get('prices', 'api\v2\customer\RetailController@index');

	Route::get('retailers', 'api\v2\customer\RetailerController@index');
	Route::post('retailers/{retailer}/follow', 'api\v2\customer\RetailerController@follow');
	Route::post('retailers/{retailer}/unfollow', 'api\v2\customer\RetailerController@unfollow');
});

<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();

# main product pages
Route::get('/','ProductController@index')->name('products.index');
Route::middleware('auth')->group(function(){
	Route::resource('products', 'ProductController')->only(['show']);
	Route::resource('products', 'ProductController')->only(['create','store','edit','update','destroy'])->middleware(['auth', 'admin']);
});

# user pages
Route::prefix('home')->name('home')->middleware('auth')->group(function() {
	Route::get('', 'HomeController@index');
	Route::get('products', 'HomeController@products')->name('.products');
	Route::get('retailers', 'HomeController@retailers')->name('.retailers');
});	


# Price model routes
Route::middleware(['auth','vendor'])->group(function () {
	Route::resource('prices', 'PriceController')->only(['index','edit','destroy','update']);
	Route::get('/prices/products/{product}/create', 'PriceController@create')->name('prices.create');
	Route::post('/prices/products/{product}', 'PriceController@store')->name('prices.store');
});

# Image model routes
Route::middleware(['auth','admin'])->group(function () {
	Route::resource('images', 'ImageController')->only(['store','update','destroy']);
	Route::prefix('images')->name('images.')->group(function(){
		Route::get('products/{product}', 'ImageController@edit')->name('edit');
		Route::patch('swap', 'ImageController@swap')->name('swap');
		Route::patch('{image}/move', 'ImageController@move')->name('move');
	});
});

# taobao routes
Route::prefix('taobao')->name('taobao.')->middleware('auth')->group(function() {
	Route::middleware('admin')->group(function () {
		Route::get('home', 'TaobaoController@home')->name('home');
		Route::prefix('products')->name('products.')->group(function(){
			Route::get('manage', 'TaobaoController@manage')->name('manage');
			Route::post('link', 'TaobaoController@link')->name('link');
			Route::post('ignore', 'TaobaoController@ignore')->name('ignore');
			Route::get('reset', 'TaobaoController@reset_products')->name('reset');
		});
		Route::prefix('prices')->name('prices.')->group(function(){
			Route::get('diffs', 'TaobaoController@diffs')->name('diffs');
			Route::get('reset', 'TaobaoController@reset_prices')->name('reset');
		});
	});
	// Route::get('', 'TaobaoController@list')->name('taobao');
	Route::get('{shop}', 'TaobaoController@index')->name('index');
	Route::get('{shop}/{product}', 'TaobaoController@show')->name('show');
});

# farfetch routes
Route::prefix('farfetch')->name('farfetch.')->middleware(['auth','admin'])->group(function () {
	Route::view('', 'farfetch.home')->name('home');
	Route::get('men/off-white', 'FarfetchController@index')->name('index');
	Route::get('men/off-white/{product}', 'FarfetchController@show')->name('show');
});

# admin helper routes
Route::prefix('admin')->middleware(['auth','admin'])->group(function () {
	Route::get('', 'AdminController@index');
});

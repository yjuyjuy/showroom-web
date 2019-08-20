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

Route::redirect('/', '/products');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/products', 'HomeController@products')->name('products');
Route::get('/home/retailers', 'HomeController@retailers')->name('retailers');
Route::resource('products', 'ProductController')->middleware(['auth','admin'])->except(['index','show']);
Route::resource('products', 'ProductController')->only(['index','show']);

Route::middleware(['auth','vendor'])->group(function () {
	Route::resource('prices', 'PriceController')->except(['create','store','index','show']);
	Route::get('/vendors/prices', 'PriceController@index')->name('prices.index');
	Route::get('/products/{product}/prices/create', 'PriceController@create')->name('prices.create');
	Route::post('/products/{product}/prices', 'PriceController@store')->name('prices.store');
});

Route::middleware(['auth', 'admin'])->group(function () {
	Route::get('/taobao/home', 'TaobaoController@home')->name('taobao.home');
	Route::get('/taobao/admin', 'TaobaoController@admin')->name('taobao.admin');
	Route::post('/taobao/product/link', 'TaobaoController@link');
	Route::post('/taobao/product/ignore', 'TaobaoController@ignore');
	Route::get('/taobao/{shop}/diffs', 'TaobaoController@diffs')->name('taobao.diffs');
	Route::get('/taobao/{shop}/reset', 'TaobaoController@reset');
});
Route::get('/taobao', 'TaobaoController@list')->name('taobao');
Route::get('/taobao/{shop}', 'TaobaoController@index')->name('taobao.index');
Route::get('/taobao/{shop}/{product}', 'TaobaoController@show')->name('taobao.show');

Route::middleware(['auth','admin'])->group(function () {
	Route::get('/products/{product}/images', 'ImageController@edit')->name('images.edit');
	Route::patch('/images/swap', 'ImageController@swap')->name('images.swap');
	Route::patch('/images/{image}/move', 'ImageController@move')->name('images.move');
	Route::resource('images', 'ImageController')->only(['store','update','destroy']);
	Route::get('/farfetch/men/off-white','FarfetchController@index')->name('farfetch.index');
	Route::get('/farfetch/men/off-white/{product}','FarfetchController@show')->name('farfetch.show');
	Route::get('/logs','LogController@index')->name('logs');
	Route::delete('/logs/{log}','LogController@destroy')->name('logs.destroy');
	Route::get('/admin','AdminController@index');
});

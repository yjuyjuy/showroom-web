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

Route::get('/', function () {
	return redirect(route('products.index'));
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('products', 'ProductController')->middleware(['auth','admin'])->except(['index','show']);
Route::resource('products', 'ProductController')->only(['index','show']);
Route::get('/farfetch/products','FarfetchController@index')->middleware(['auth','admin'])->name('farfetch.index');
Route::get('/farfetch/products/{product}','FarfetchController@show')->middleware(['auth','admin'])->name('farfetch.show');

Route::middleware(['auth','vendor'])->group(function () {
	Route::resource('prices', 'PriceController')->except(['create','store','index','show']);
	Route::get('/vendors/prices', 'PriceController@index')->name('prices.index');
	Route::get('/products/{product}/prices/create', 'PriceController@create')->name('prices.create');
	Route::post('/products/{product}/prices', 'PriceController@store')->name('prices.store');
});

Route::middleware(['auth','admin'])->group(function () {
	Route::get('/products/{product}/images', 'ImageController@edit')->name('images.edit');
	Route::patch('/images/swap', 'ImageController@swap')->name('images.swap');
	Route::patch('/images/{image}/move', 'ImageController@move')->name('images.move');
	Route::resource('images', 'ImageController')->only(['store','update','destroy']);
});
Route::get('/logs','LogController@index')->middleware(['auth','admin'])->name('logs');
Route::delete('/logs/{log}','LogController@destroy')->middleware(['auth','admin'])->name('logs.destroy');
Route::get('/language', function () {
	return App::getLocale();
});

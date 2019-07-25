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

Route::resource('users', 'UsersController');

Route::resource('products', 'ProductsController')->middleware(['auth','admin'])->except(['index','show']);
Route::get('/products', 'ProductsController@index')->name('products.index');
Route::get('/products/{product}', 'ProductsController@show')->name('products.show');

Route::middleware(['auth','vendor'])->group(function () {
	Route::resource('prices', 'PricesController')->except(['create','store','index','show']);
	Route::get('/vendors/prices', 'PricesController@index')->name('prices.index');
	Route::get('/products/{product}/prices/create', 'PricesController@create')->name('prices.create');
	Route::post('/products/{product}/prices', 'PricesController@store')->name('prices.store');
});

Route::middleware(['auth','admin'])->group(function () {
	Route::get('/products/{product}/images', 'ImagesController@edit')->name('images.edit');
	Route::patch('/images/swap', 'ImagesController@swap')->name('images.swap');
	Route::patch('/images/{image}/move', 'ImagesController@move')->name('images.move');
	Route::resource('images', 'ImagesController')->only(['store','update','destroy']);
});
Route::get('/logs','LogsController@index')->middleware(['auth','admin'])->name('logs');
Route::get('/language', function () {
	return App::getLocale();
});
// deprecate
// Route::prefix('vendors')->name('vendors.')->middleware(['auth','vendor'])->group(function () {
// 	Route::resource('products', 'VendorsProductsController');
// });
//
// Route::prefix('admin')->name('admin.')->middleware(['auth','admin'])->group(function () {
// 	Route::resource('products', 'AdminsProductsController');
// });

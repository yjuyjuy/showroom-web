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

Route::resource('users', 'UsersController')->middleware('auth');
Route::get('/products', 'ProductsController@index')->name('products.index');
Route::get('/products/{product}', 'ProductsController@show')->name('products.show');
Route::resource('products', 'ProductsController')->middleware(['auth','admin'])->except(['index','show']);
Route::middleware(['auth','vendor'])->group(function () {
	Route::get('/products/{product}/prices/create', 'PricesController@create')->name('prices.create');
	Route::patch('/products/{product}/prices', 'PricesController@store')->name('prices.store');
	Route::resource('price', 'PricesController')->except(['create','store','index']);
	Route::get('/vendors/prices', 'PricesController@index')->name('prices.index');
});



// deprecate
// Route::prefix('vendors')->name('vendors.')->middleware(['auth','vendor'])->group(function () {
// 	Route::resource('products', 'VendorsProductsController');
// });
//
// Route::prefix('admin')->name('admin.')->middleware(['auth','admin'])->group(function () {
// 	Route::resource('products', 'AdminsProductsController');
// });

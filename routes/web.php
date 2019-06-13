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

Route::view('/', 'welcome');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/users', 'UsersController@index')->middleware(['auth','admin']);

Route::prefix('vendors')->name('vendors.')->middleware(['auth','vendor'])->group(function () {
	Route::resource('products', 'VendorsProductsController');
});

Route::prefix('admin')->name('admin.')->middleware(['auth','admin'])->group(function () {
	Route::resource('products', 'AdminsProductsController');
});

Route::get('/admin/log', 'LogsController@index')->name('log');

Route::resource('products', 'ProductsController');
Route::resource('prices', 'PricesController')->middleware(['auth','vendor']);

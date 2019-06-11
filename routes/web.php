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
	return redirect('/products');
});

Auth::routes();

Route::get('/', function () {
	return view('layouts.show');
});
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/users', 'ProductsController@index');

Route::get('/vendor/{vendor}/products', 'VendorsController@index')->middleware('auth')->middleware('vendor');
Route::get('/vendor/{vendor}/products/{product}', 'VendorsController@show')->middleware('auth')->middleware('vendor');

Route::resource('products', 'ProductsController');

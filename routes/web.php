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
Route::get('/users', 'UsersController@index')->middleware('admin');

Route::get('/vendor/{vendor}/products', 'VendorsController@index')->middleware('auth')->middleware('vendor')->name('admin');
Route::get('/vendor/{vendor}/products/{product}', 'VendorsController@show')->middleware('auth')->middleware('vendor');

Route::get('/admin/log', 'LogsController@index')->name('log');

Route::resource('products', 'ProductsController');

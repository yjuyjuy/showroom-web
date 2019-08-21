<?php

Auth::routes();

Route::redirect('', 'products');

# User model
Route::middleware('auth')->group(function() {
	Route::get('home', 'HomeController@index')->name('home');
	Route::get('home/settings', 'UserController@edit')->name('settings.edit');
	Route::patch('home/settings', 'UserController@update')->name('settings.update');
	Route::get('home/followings/products', 'ProductController@following')->name('following.products');
	Route::get('home/followings/retailers', 'RetailerController@following')->name('following.retailers');
});

# Product model
Route::get('products', 'ProductController@index')->name('products.index');
Route::middleware('auth')->group(function() {
	Route::post('products', 'ProductController@store')->name('products.store')->middleware('admin');
	Route::get('products/create', 'ProductController@create')->name('products.create')->middleware('admin');
	Route::get('products/random', 'ProductController@random')->name('products.random');
	Route::get('products/{product}', 'ProductController@show')->name('products.show');
	Route::patch('products/{product}', 'ProductController@update')->name('products.update')->middleware('admin');
	Route::delete('products/{product}', 'ProductController@destroy')->name('products.destroy')->middleware('admin');
	Route::get('products/{product}/edit', 'ProductController@edit')->name('products.edit')->middleware('admin');
	Route::patch('products/{product}/follow', 'ProductController@follow')->name('follow.product');
	Route::patch('products/{product}/unfollow', 'ProductController@unfollow')->name('unfollow.product');
});

# Price model
Route::middleware(['auth', 'vendor'])->group(function() {
	Route::get('prices', 'PriceController@index')->name('prices.index');
	Route::patch('prices/{price}', 'PriceController@update')->name('prices.update');
	Route::delete('prices/{price}', 'PriceController@destroy')->name('prices.destroy');
	Route::get('prices/{price}/edit', 'PriceController@edit')->name('prices.edit');
	Route::post('products/{product}/prices', 'PriceController@store')->name('prices.store');
	Route::get('products/{product}/prices/create', 'PriceController@create')->name('prices.create');
});

# Image model
Route::middleware(['auth', 'admin'])->group(function() {
	Route::post('images', 'ImageController@store')->name('images.store');
	Route::patch('images/swap', 'ImageController@swap')->name('images.swap');
	Route::patch('images/{image}', 'ImageController@update')->name('images.update');
	Route::delete('images/{image}', 'ImageController@destroy')->name('images.destroy');
	Route::patch('images/{image}/move', 'ImageController@move')->name('images.move');
	Route::get('products/{product}/images', 'ImageController@edit')->name('images.edit');
});

# Retailer
Route::middleware('auth')->group(function () {
	Route::get('retailer/search', 'RetailerController@search')->name('retailer.search');
	Route::patch('retailer/{retailer}/follow', 'RetailerController@follow')->name('follow.retailer');
	Route::patch('retailer/{retailer}/unfollow', 'RetailerController@unfollow')->name('unfollow.retailer');
	Route::get('retailer/{retailer}/products', 'RetailerController@index')->name('retailer.products.index');
	Route::get('retailer/{retailer}/products/random', 'RetailerController@random')->name('retailer.products.random');
	Route::get('retailer/{retailer}/products/{product}', 'RetailerController@show')->name('retailer.products.show');
});

# Taobao
Route::middleware('auth')->group(function () {
	Route::get('taobao/admin', 'TaobaoController@admin')->name('taobao.admin')->middleware('taobao');
	Route::get('taobao/{shop}', 'TaobaoController@index')->name('taobao.products.index');
	Route::get('taobao/{shop}/{product}', 'TaobaoController@show')->name('taobao.products.show');
	Route::get('taobao/admin/edit', 'TaobaoController@edit')->name('taobao.admin.edit')->middleware('taobao');
	Route::get('taobao/admin/diffs', 'TaobaoController@diffs')->name('taobao.admin.diffs')->middleware('taobao');
	Route::post('taobao/admin/link', 'TaobaoController@link')->name('taobao.admin.link')->middleware('taobao');
	Route::post('taobao/admin/unlink', 'TaobaoController@unlink')->name('taobao.admin.unlink')->middleware('taobao');
	Route::post('taobao/admin/ignore', 'TaobaoController@ignore')->name('taobao.admin.ignore')->middleware('taobao');
});

# Farfetch
Route::middleware(['auth', 'admin'])->group(function() {
	Route::get('farfetch', 'FarfetchController@index')->name('farfetch.index');
	Route::get('farfetch/{product}', 'FarfetchController@show')->name('farfetch.show');
});

# admin helper routes
Route::get('admin', 'AdminController@index')->name('admin')->middleware(['auth', 'admin']);

# token
Route::get('{token}', 'UrlTokenController@find')->name('token')->middleware(['auth', 'throttle:15,1']);

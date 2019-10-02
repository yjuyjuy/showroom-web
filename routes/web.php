<?php

Auth::routes(['verify' => true]);

Route::redirect('', 'products');

# User model
Route::middleware('auth')->group(function () {
	Route::get('home', 'HomeController@index')->name('home')->middleware('verified');
	Route::get('suggestion/create', 'SuggestionController@create')->name('suggestion.create');
	Route::post('suggestion', 'SuggestionController@store')->name('suggestion.store');
	Route::get('account/status', 'AccountController@status')->name('account.status');
	Route::post('account/status/request', 'AccountController@request')->name('account.request');
	// Route::get('account/settings/edit', 'AccountController@edit')->name('account.settings.edit');
	// Route::patch('account/settings', 'AccountController@update')->name('account.settings.update');
	Route::get('following/products', 'ProductController@following')->name('following.products');
	Route::get('following/retailers', 'RetailerController@following')->name('following.retailers');
	Route::get('following/vendors', 'VendorController@following')->name('following.vendors')->middleware('reseller');
	// Route::get('following/vendors/{vendor}', 'VendorController@edit')->name('following.vendors.edit')->middleware('reseller');
	// Route::patch('following/vendors/{vendor}', 'VendorController@update')->name('following.vendors.edit')->middleware('reseller');
});

# Product model
Route::get('products', 'ProductController@index')->name('products.index');
Route::middleware('auth')->group(function () {
	Route::post('products', 'ProductController@store')->name('products.store')->middleware('admin');
	Route::get('products/create', 'ProductController@create')->name('products.create')->middleware('admin');
	Route::get('products/random', 'ProductController@random')->name('products.random');
	Route::get('products/{product}', 'ProductController@show')->name('products.show');
	Route::patch('products/{product}', 'ProductController@update')->name('products.update')->middleware('admin');
	Route::delete('products/{product}', 'ProductController@destroy')->name('products.destroy')->middleware('admin');
	Route::get('products/{product}/edit', 'ProductController@edit')->name('products.edit')->middleware('admin');
	Route::post('products/{product}/follow', 'FollowProductController@follow')->name('follow.product');
	Route::post('products/{product}/unfollow', 'FollowProductController@unfollow')->name('unfollow.product');
});

# Price model
Route::middleware(['auth', 'vendor'])->group(function () {
	Route::get('prices', 'PriceController@index')->name('prices.index');
	Route::patch('prices/{price}', 'PriceController@update')->name('prices.update');
	Route::delete('prices/{price}', 'PriceController@destroy')->name('prices.destroy');
	Route::get('prices/{price}/edit', 'PriceController@edit')->name('prices.edit');
	Route::post('products/{product}/prices', 'PriceController@store')->name('prices.store');
	Route::get('products/{product}/prices/create', 'PriceController@create')->name('prices.create');
});

# Image model
Route::middleware(['auth', 'admin'])->group(function () {
	Route::post('images', 'ImageController@store')->name('images.store');
	Route::patch('images/swap', 'ImageController@swap')->name('images.swap');
	Route::patch('images/{image}', 'ImageController@update')->name('images.update');
	Route::delete('images/{image}', 'ImageController@destroy')->name('images.destroy');
	Route::patch('images/{image}/move', 'ImageController@move')->name('images.move');
	Route::get('products/{product}/images', 'ImageController@edit')->name('images.edit');
});

# Retailer
Route::middleware('auth')->group(function () {
	Route::post('retailer/{retailer}/follow', 'FollowRetailerController@follow')->name('follow.retailer');
	Route::post('retailer/{retailer}/unfollow', 'FollowRetailerController@unfollow')->name('unfollow.retailer');
	Route::get('retailer/{retailer}', 'RetailerController@index')->name('retailer.products.index');
	Route::get('retailer/{retailer}/products/{product}', 'RetailerController@show')->name('retailer.products.show');
});

# Vendor
Route::middleware(['auth', 'reseller'])->group(function () {
	Route::get('reseller/products', 'ResellerController@index')->name('reseller.products.index');
	Route::post('vendor/{vendor}/unfollow', 'FollowVendorController@unfollow')->name('unfollow.vendor');
	Route::get('vendor/{vendor}', 'VendorController@index')->name('vendor.products.index');
	Route::get('vendor/{vendor}/products/{product}', 'VendorController@show')->name('vendor.products.show');
});

# Taobao
Route::middleware('auth')->group(function () {
	Route::post('taobao/link', 'TaobaoAdminController@link')->name('taobao.admin.link');
	Route::post('taobao/unlink', 'TaobaoAdminController@unlink')->name('taobao.admin.unlink');
	Route::post('taobao/ignore', 'TaobaoAdminController@ignore')->name('taobao.admin.ignore');
	Route::get('taobao/admin', 'TaobaoAdminController@index')->name('admin.taobao.index')->middleware('admin');
	Route::get('taobao/admin/links', 'TaobaoAdminController@links')->name('admin.taobao.links')->middleware('admin');
	Route::get('taobao/admin/ignored', 'TaobaoAdminController@ignored')->name('admin.taobao.ignored')->middleware('admin');
	Route::get('taobao/admin/linked', 'TaobaoAdminController@linked')->name('admin.taobao.linked')->middleware('admin');
	Route::get('taobao/{shop}', 'TaobaoController@index')->name('taobao.products.index');
	Route::get('taobao/{shop}/admin', 'TaobaoAdminController@admin')->name('taobao.admin');
	Route::get('taobao/{shop}/admin/links', 'TaobaoAdminController@links')->name('taobao.admin.links');
	Route::get('taobao/{shop}/admin/linked', 'TaobaoAdminController@linked')->name('taobao.admin.linked');
	Route::get('taobao/{shop}/admin/ignored', 'TaobaoAdminController@ignored')->name('taobao.admin.ignored');
	Route::get('taobao/{shop}/admin/diffs', 'TaobaoAdminController@diffs')->name('taobao.admin.diffs');
	Route::get('taobao/{shop}/{product}', 'TaobaoController@show')->name('taobao.products.show');
});

# Websites
Route::middleware(['auth'])->group(function () {
	Route::get('browse', 'WebsiteController@index')->name('websites');
	# farfetch
	Route::get('Farfetch', 'FarfetchController@index')->name('farfetch.index');
	Route::get('Farfetch/designers', 'FarfetchController@designers')->name('farfetch.designers');
	Route::get('Farfetch/categories', 'FarfetchController@categories')->name('farfetch.categories');
	Route::get('Farfetch/designers/{designer}', 'FarfetchController@index')->name('farfetch.designers.index');
	Route::get('Farfetch/categories/{category}', 'FarfetchController@index')->name('farfetch.categories.index');
	Route::get('Farfetch/{product}', 'FarfetchController@show')->name('farfetch.show');
	# end clothing
	Route::get('End', 'EndController@index')->name('end.index');
	Route::get('End/brands', 'EndController@brands')->name('end.brands');
	Route::get('End/departments', 'EndController@departments')->name('end.departments');
	Route::get('End/brands/{brand}', 'EndController@index')->name('end.brands.index');
	Route::get('End/departments/{department}', 'EndController@index')->name('end.departments.index');
	Route::get('End/{product}', 'EndController@show')->name('end.show');
	# off---white
	Route::get('Off-White', 'OffWhiteController@index')->name('offwhite.index');
	Route::get('Off-White/{product}', 'OffWhiteController@show')->name('offwhite.show');
});

# admin helper routes
Route::middleware(['auth', 'admin'])->group(function () {
	Route::get('admin', 'AdminController@index')->name('admin.index');
	Route::view('admin/inbox', 'admin.inbox')->name('admin.inbox');
	Route::get('requests', 'RequestController@index')->name('requests.index');
	Route::get('suggestions', 'SuggestionController@index')->name('suggestions.index');
	Route::get('admin/{function}', 'AdminController@call')->name('admin.call');
	Route::post('requests/agree', 'RequestController@agree')->name('requests.agree');
	Route::post('requests/reject', 'RequestController@reject')->name('requests.reject');
});

Route::get('{slug}', function() { abort(404); })->middleware(['auth', 'throttle:10,1']);

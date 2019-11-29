<?php

Auth::routes(['verify' => true]);

Route::redirect('', 'products');

# User model
Route::middleware('auth')->group(function () {
	Route::get('home', 'HomeController@index')->name('home');
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
Route::get('products/random', 'ProductController@random')->name('products.random');
Route::middleware('auth')->group(function () {
	Route::post('products', 'ProductController@store')->name('products.store')->middleware('admin');
	Route::get('products/create', 'ProductController@create')->name('products.create')->middleware('admin');
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
	Route::post('prices/{price}/+/{size}', 'PriceController@add')->name('prices.add');
	Route::post('prices/{price}/-/{size}', 'PriceController@subtract')->name('prices.subtract');
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
Route::redirect('/retailer/Dior', '/dior');
Route::redirect('/retailer/Gucci', '/gucci');
Route::redirect('/retailer/EndClothing', '/end');
Route::redirect('/retailer/Farfetch', '/farfetch');
Route::redirect('/retailer/Balenciaga', '/balenciaga');
Route::redirect('/retailer/Louis Vuitton', '/louis-vuitton');

Route::get('retailer/{retailer}', 'RetailerController@index')->name('retailer.products.index');
Route::get('retailer/{retailer}/products/{product}', 'RetailerController@show')->name('retailer.products.show');
Route::middleware('auth')->group(function () {
	Route::post('retailer/{retailer}/follow', 'FollowRetailerController@follow')->name('follow.retailer');
	Route::post('retailer/{retailer}/unfollow', 'FollowRetailerController@unfollow')->name('unfollow.retailer');
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
Route::middleware('auth')->group(function () {
	Route::get('websites', 'WebsiteController@index')->name('websites.index');
	# boutique sites
	Route::get('farfetch', 'FarfetchController@index')->name('farfetch.index');
	Route::get('farfetch/designers', 'FarfetchController@designers')->name('farfetch.designers');
	Route::get('farfetch/categories', 'FarfetchController@categories')->name('farfetch.categories');
	Route::get('farfetch/designers/{designer}', 'FarfetchController@index')->name('farfetch.designers.index');
	Route::get('farfetch/categories/{category}', 'FarfetchController@index')->name('farfetch.categories.index');
	Route::get('farfetch/{product}', 'FarfetchController@show')->name('farfetch.show');
	Route::get('farfetch/{farfetch_product}/export', 'FarfetchController@export')->name('farfetch.export')->middleware('can:create,App\Product');
	Route::get('farfetch/{farfetch_product}/merge/{product}', 'FarfetchController@merge')->name('farfetch.merge')->middleware('can:create,App\Product');
	Route::get('farfetch/{farfetch_product}/unlink', 'FarfetchController@unlink')->name('farfetch.unlink')->middleware('can:create,App\Product');
	# end clothing
	Route::get('end', 'EndController@index')->name('end.index');
	Route::get('end/brands', 'EndController@brands')->name('end.brands');
	Route::get('end/departments', 'EndController@departments')->name('end.departments');
	Route::get('end/brands/{brand}', 'EndController@index')->name('end.brands.index');
	Route::get('end/departments/{department}', 'EndController@index')->name('end.departments.index');
	Route::get('end/{product}', 'EndController@show')->name('end.show');
	Route::get('end/{end_product}/export', 'EndController@export')->name('end.export')->middleware('can:create,App\Product');
	Route::get('end/{end_product}/unlink', 'EndController@unlink')->name('end.unlink')->middleware('can:create,App\Product');
	Route::get('end/{end_product}/merge/{product}', 'EndController@merge')->name('end.merge')->middleware('can:create,App\Product');

	# official sites
	Route::get('off-white', 'OffWhiteController@index')->name('offwhite.index');
	Route::get('off-white/categories', 'OffWhiteController@categories')->name('offwhite.categories');
	Route::get('off-white/categories/{category}', 'OffWhiteController@index')->name('offwhite.categories.index');
	Route::get('off-white/{product}', 'OffWhiteController@show')->name('offwhite.show');
	Route::get('off-white/{offwhite_product}/export', 'OffWhiteController@export')->name('offwhite.export')->middleware('can:create,App\Product');
	Route::get('off-white/{offwhite_product}/unlink', 'OffWhiteController@unlink')->name('offwhite.unlink')->middleware('can:create,App\Product');
	Route::get('off-white/{offwhite_product}/merge/{product}', 'OffWhiteController@merge')->name('offwhite.merge')->middleware('can:create,App\Product');

	Route::get('dior', 'DiorController@index')->name('dior.index');
	Route::get('dior/categories/{category}', 'DiorController@index')->name('dior.categories.index');
	Route::get('dior/{product}', 'DiorController@show')->name('dior.show');
	Route::get('dior/{dior_product}/export', 'DiorController@export')->name('dior.export')->middleware('can:create,App\Product');
	Route::get('dior/{dior_product}/unlink', 'DiorController@unlink')->name('dior.unlink')->middleware('can:create,App\Product');
	Route::get('dior/{dior_product}/merge/{product}', 'DiorController@merge')->name('dior.merge')->middleware('can:create,App\Product');

	Route::get('louis-vuitton', 'LouisVuittonController@index')->name('louisvuitton.index');
	Route::get('louis-vuitton/categories/{category}', 'LouisVuittonController@index')->name('louisvuitton.categories.index');
	Route::get('louis-vuitton/{product}', 'LouisVuittonController@show')->name('louisvuitton.show');
	Route::get('louis-vuitton/{lv_product}/export', 'LouisVuittonController@export')->name('louisvuitton.export')->middleware('can:create,App\Product');
	Route::get('louis-vuitton/{lv_product}/unlink', 'LouisVuittonController@unlink')->name('louisvuitton.unlink')->middleware('can:create,App\Product');
	Route::get('louis-vuitton/{lv_product}/merge/{product}', 'LouisVuittonController@merge')->name('louisvuitton.merge')->middleware('can:create,App\Product');

	Route::get('gucci', 'GucciController@index')->name('gucci.index');
	Route::get('gucci/categories/{category}', 'GucciController@index')->name('gucci.categories.index');
	Route::get('gucci/{product}', 'GucciController@show')->name('gucci.show');
	Route::get('gucci/{gucci_product}/export', 'GucciController@export')->name('gucci.export')->middleware('can:create,App\Product');
	Route::get('gucci/{gucci_product}/unlink', 'GucciController@unlink')->name('gucci.unlink')->middleware('can:create,App\Product');
	Route::get('gucci/{gucci_product}/merge/{product}', 'GucciController@merge')->name('gucci.merge')->middleware('can:create,App\Product');

	Route::get('balenciaga', 'BalenciagaController@index')->name('balenciaga.index');
	Route::get('balenciaga/departments/{department}', 'BalenciagaController@index')->name('balenciaga.departments.index');
	Route::get('balenciaga/{product}', 'BalenciagaController@show')->name('balenciaga.show');
	Route::get('balenciaga/{balenciaga_product}/export', 'BalenciagaController@export')->name('balenciaga.export')->middleware('can:create,App\Product');
	Route::get('balenciaga/{balenciaga_product}/unlink', 'BalenciagaController@unlink')->name('balenciaga.unlink')->middleware('can:create,App\Product');
	Route::get('balenciaga/{balenciaga_product}/merge/{product}', 'BalenciagaController@merge')->name('balenciaga.merge')->middleware('can:create,App\Product');
});

# admin helper routes
Route::middleware(['auth', 'admin'])->group(function () {
	Route::get('admin', 'AdminController@index')->name('admin.index');
	Route::view('admin/inbox', 'admin.inbox')->name('admin.inbox');
	Route::get('requests', 'RequestController@index')->name('requests.index');
	Route::get('suggestions', 'SuggestionController@index')->name('suggestions.index');
	Route::get('admin/{function}', 'AdminController@call')->name('admin.call');
	Route::post('suggestions/archive/{suggestion}', 'SuggestionController@archive')->name('suggestion.archive');
	Route::post('requests/agree/{user}', 'RequestController@agree')->name('requests.agree');
	Route::post('requests/reject/{user}', 'RequestController@reject')->name('requests.reject');
	Route::get('logs', 'LogController@index')->name('logs.index');
	Route::view('users', 'admin.users')->name('admin.users');
});

Route::get('{slug}', function() { abort(404); })->middleware(['auth', 'throttle:10,1']);

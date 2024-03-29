<?php

Auth::routes(['verify' => true]);
Route::redirect('/', '/products');

Route::redirect('app', 'download')->name('app');
Route::get('download', 'DownloadController@index')->name('download');
Route::get('language', 'LanguageController@edit')->name('language.edit');
Route::post('language', 'LanguageController@update')->name('language.update');

# User model
Route::middleware('auth')->group(function () {
	Route::view('registered', 'auth.registered')->name('registered');

	Route::get('suggestion/create', 'SuggestionController@create')->name('suggestion.create');
	Route::post('suggestion', 'SuggestionController@store')->name('suggestion.store');

	Route::patch('account', 'AccountController@update')->name('account.update');
	Route::get('account/edit', 'AccountController@edit')->name('account.edit');

	Route::get('following/products', 'ProductController@following')->name('following.products');
	Route::get('following/vendors', 'VendorController@following')->name('following.vendors')->middleware('reseller');
});
Route::get('following/retailers', 'RetailerController@following')->name('following.retailers');

# Product model
Route::middleware('auth')->group(function () {
	Route::middleware('admin')->group(function () {
		Route::post('products', 'ProductController@store')->name('products.store')->middleware('admin');
		Route::get('products/create', 'ProductController@create')->name('products.create')->middleware('admin');
		Route::patch('products/{product}', 'ProductController@update')->name('products.update')->middleware('admin');
		Route::delete('products/{product}', 'ProductController@destroy')->name('products.destroy')->middleware('admin');
		Route::get('products/{product}/edit', 'ProductController@edit')->name('products.edit')->middleware('admin');
	});
	// TODO: fix products.index route for guests
	Route::get('products', 'ProductController@index')->name('products.index');
	Route::get('products/random', 'ProductController@random')->name('products.random');
	Route::get('products/{product}', 'ProductController@show')->name('products.show');
	Route::post('products/{product}/follow', 'ProductController@follow')->name('follow.product');
	Route::post('products/{product}/unfollow', 'ProductController@unfollow')->name('unfollow.product');
});

# Price model
Route::get('prices', 'PriceController@index')->name('prices.index');
Route::middleware(['auth', 'vendor'])->group(function () {
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

# Measurement model
Route::middleware(['auth', 'vendor'])->group(function () {
	Route::get('products/{product}/measurement', 'MeasurementController@create')->name('measurements.create');
	Route::post('products/{product}/measurement', 'MeasurementController@store')->name('measurements.store');
	Route::get('products/{product}/measurement/edit', 'MeasurementController@edit')->name('measurements.edit');
	Route::patch('products/{product}/measurement', 'MeasurementController@update')->name('measurements.update');
	Route::delete('products/{product}/measurement', 'MeasurementController@destroy')->name('measurements.destroy');
});

# Retailer
Route::get('retailer/{retailer}', 'RetailerController@index')->name('retailer.products.index');
Route::get('retailer/{retailer}/products/{product}', 'RetailerController@show')->name('retailer.products.show');
Route::middleware('auth')->group(function () {
	Route::post('retailer/{retailer}/follow', 'RetailerController@follow')->name('follow.retailer');
	Route::post('retailer/{retailer}/unfollow', 'RetailerController@unfollow')->name('unfollow.retailer');
});

# Vendor
Route::middleware(['auth', 'reseller'])->group(function () {
	Route::get('reseller/products', 'ResellerController@index')->name('reseller.products.index');
	Route::post('vendor/{vendor}/follow', 'VendorController@follow')->name('follow.vendor');
	Route::post('vendor/{vendor}/unfollow', 'VendorController@unfollow')->name('unfollow.vendor');
	Route::get('vendor/{vendor}', 'VendorController@index')->name('vendor.products.index');
	Route::get('vendor/{vendor}/products/{product}', 'VendorController@show')->name('vendor.products.show');
});

// # Taobao
// Route::middleware('auth')->group(function () {
// 	Route::post('taobao/link', 'TaobaoAdminController@link')->name('taobao.admin.link');
// 	Route::post('taobao/unlink', 'TaobaoAdminController@unlink')->name('taobao.admin.unlink');
// 	Route::post('taobao/ignore', 'TaobaoAdminController@ignore')->name('taobao.admin.ignore');
// 	Route::get('taobao/admin', 'TaobaoAdminController@index')->name('admin.taobao.index')->middleware('admin');
// 	Route::get('taobao/admin/links', 'TaobaoAdminController@links')->name('admin.taobao.links')->middleware('admin');
// 	Route::get('taobao/admin/ignored', 'TaobaoAdminController@ignored')->name('admin.taobao.ignored')->middleware('admin');
// 	Route::get('taobao/admin/linked', 'TaobaoAdminController@linked')->name('admin.taobao.linked')->middleware('admin');
// 	Route::get('taobao/{shop}', 'TaobaoController@index')->name('taobao.products.index');
// 	Route::get('taobao/{shop}/admin', 'TaobaoAdminController@admin')->name('taobao.admin');
// 	Route::get('taobao/{shop}/admin/links', 'TaobaoAdminController@links')->name('taobao.admin.links');
// 	Route::get('taobao/{shop}/admin/linked', 'TaobaoAdminController@linked')->name('taobao.admin.linked');
// 	Route::get('taobao/{shop}/admin/ignored', 'TaobaoAdminController@ignored')->name('taobao.admin.ignored');
// 	Route::get('taobao/{shop}/admin/diffs', 'TaobaoAdminController@diffs')->name('taobao.admin.diffs');
// 	Route::get('taobao/{shop}/{product}', 'TaobaoController@show')->name('taobao.products.show');
// });

# Websites
Route::middleware('auth')->group(function () {
	Route::get('websites', 'WebsiteController@index')->name('websites.index');
	# boutique sites
	Route::get('farfetch', 'FarfetchController@index')->name('farfetch.index');
	Route::get('farfetch/{product}', 'FarfetchController@show')->name('farfetch.show');
	Route::get('farfetch/{farfetch_product}/export', 'FarfetchController@export')->name('farfetch.export')->middleware('can:create,App\Product');
	Route::get('farfetch/{farfetch_product}/merge/{product}', 'FarfetchController@merge')->name('farfetch.merge')->middleware('can:create,App\Product');
	Route::get('farfetch/{farfetch_product}/unlink', 'FarfetchController@unlink')->name('farfetch.unlink')->middleware('can:create,App\Product');

	Route::get('ssense', 'SsenseController@index')->name('ssense.index');
	Route::get('ssense/{product}', 'SsenseController@show')->name('ssense.show');
	Route::get('ssense/{ssense_product}/export', 'SsenseController@export')->name('ssense.export')->middleware('can:create,App\Product');
	Route::get('ssense/{ssense_product}/merge/{product}', 'SsenseController@merge')->name('ssense.merge')->middleware('can:create,App\Product');
	Route::get('ssense/{ssense_product}/unlink', 'SsenseController@unlink')->name('ssense.unlink')->middleware('can:create,App\Product');

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
	Route::get('admin/{function}', 'AdminController@call')->name('admin.call');

	Route::get('requests', 'RequestController@index')->name('requests.index');
	Route::post('requests/agree/{user}', 'RequestController@agree')->name('requests.agree');
	Route::post('requests/reject/{user}', 'RequestController@reject')->name('requests.reject');

	Route::get('logs', 'LogController@index')->name('logs.index');
	Route::view('users', 'admin.users')->name('admin.users');
});

Route::get('storage/images/{options}/{filename}', 'ImageCacheController@getResponse')->where('filename', '.+');;

// Google cloud health check
Route::get('health', 'HealthController');

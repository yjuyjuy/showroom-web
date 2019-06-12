<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vendor;
use App\Product;

class VendorsProductsController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$vendor = auth()->user()->vendor;
		$data = $request->validate([
			'show_available' => '',
			'show_unavailable' => '',
			'category.*' => 'sometimes|exists:categories,id',
			'season.*' => 'sometimes|exists:seasons,id',
			'color.*' => 'sometimes|exists:colors,id',
			'brand.*' => 'sometimes|exists:brands,id',
			'sort' => 'sometimes|exists:sortmethods,name',
		]);

		$per_page = 4;
		$query = Product::with([
			'brand',
			'prices' => function ($query) use ($vendor) {
				$query->where('vendor_id', $vendor->id);
			},
			'images' => function ($query) {
				$query->orderBy('website_id', 'ASC')->orderBy('type_id', 'ASC');
			}
		]);
		foreach (['category','color','brand','season'] as $field) {
			if ($request->input($field)) {
				$query->whereIn("{$field}_id", $data[$field]);
			}
		}
		$products = Product::sort_and_get($data['sort']??'default', $query);

		if ($request->input('show_available')) {
			$products = $products->filter(function ($product) {
				return $product->prices->isNotEmpty();
			});
		}
		$request->flash();
		return view('vendors.products.index', compact('products'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$vendor = new Vendor();
		return view('vendors.products.create', compact('vendor'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Product  $product
	 * @return \Illuminate\Http\Response
	 */
	public function show(Product $product)
	{
		$vendor = auth()->user()->vendor;
		$product->load(['images',  'prices' => function ($query) use ($vendor) {
			$query->where('vendor_id', $vendor->id);
		}]);
		$sizes = [];
		$product->prices()->pluck('data')->map(function ($item) use (&$sizes) {
			$sizes = array_merge_recursive($sizes, $item);
		});
		$sizes = array_map(function ($item) {
			return (is_array($item))? min($item) : $item;
		}, $sizes);
		return view('vendors.products.show', compact('product', 'sizes'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Product  $product
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Product $product)
	{
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Product  $product
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Product $product)
	{
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Product  $product
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Product $product)
	{

		//
	}
}

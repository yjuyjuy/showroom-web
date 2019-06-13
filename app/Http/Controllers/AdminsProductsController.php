<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class AdminsProductsController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
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
			'brand', 'prices', 'images' => function ($query) {
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
		return view('admin.products.index', compact('products'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Product  $product
	 * @return \Illuminate\Http\Response
	 */
	public function show(Product $product)
	{
		$product->load(['images',  'prices']);
		$sizes = $product->size_cost_price;
		return view('admin.products.show', compact('product', 'sizes'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Product  $product
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Product $product)
	{
		//
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
		//
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

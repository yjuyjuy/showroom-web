<?php

namespace App\Http\Controllers;

use App\Category;
use App\Color;
use App\Product;
use App\Season;
use App\Brand;
use App\Sortmethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductsController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$data = $request->validate([
			'category.*' => 'sometimes|exists:categories,id',
			'season.*' => 'sometimes|exists:seasons,id',
			'color.*' => 'sometimes|exists:colors,id',
			'brand.*' => 'sometimes|exists:brands,id',
			'sort' => 'sometimes|exists:sortmethods,name',
		]);
		$per_page = 4;
		$query = Product::with([
			'category','color','season','brand','prices','images' => function ($query) {
				$query->orderBy('website_id', 'ASC')->orderBy('type_id', 'ASC');
			}
		]);
		foreach (['category','color','brand','season'] as $field) {
			if ($request->input($field)) {
				$query->whereIn("{$field}_id", $data[$field]);
			}
		}
		$products = $this->sort_and_get($data['sort']??'default', $query);

		// $links = $products->appends($data)->links();
		$links = '';
		$request->flash();
		return view('products.index', compact('products', 'links'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$product = new Product();
		return view('products.create', compact('product'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$product = Product::create($this->validateRequest());
		return redirect("/products/{$product->id}");
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Product  $product
	 * @return \Illuminate\Http\Response
	 */
	public function show(Product $product)
	{
		$product->load('images', 'prices');
		$sizes = [];
		$product->prices()->pluck('data')->map(function ($item, $key) use (&$sizes) {
			$sizes = array_merge_recursive($sizes, json_decode($item, true));
		});
		$sizes = array_map(function ($item) {
			return (is_array($item))? min($item) : $item;
		}, $sizes);
		return view('products.show', compact('product', 'sizes'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Product  $product
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Product $product)
	{
		$this->authorize('update', $product);
		return view('products.edit', compact('product'));
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
		$product->update($this->validateRequest());
		return redirect("/products/{$product->id}");
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

	public function sort_and_get($name = 'default', $query)
	{
		if ($name === 'default') {
			return $query->orderBy('season_id', 'desc')->orderBy('id', 'asc')->get();
		}

		if ($name === 'price low to high') {
			return $query->get()->sortBy(
				function ($product, $key) {
					return $product->minPrice(INF);
				}
			);
		}

		if ($name === 'price high to low') {
			return $query->get()->sortByDesc(
				function ($product, $key) {
					return $product->minPrice(0);
				}
			);
		}

		if ($name === 'hottest') {
			return $query->orderBy('season_id', 'desc')->get();
		}

		if ($name === 'best selling') {
			return $query->orderBy('season_id', 'asc')->get();
		}

		if ($name === 'newest') {
			return $query->orderBy('season_id', 'desc')->orderBy('id', 'asc')->get();
		}

		if ($name === 'oldest') {
			return $query->orderBy('season_id', 'asc')->orderBy('id', 'asc')->get();
		}
		return $query->orderBy('season_id', 'desc')->orderBy('id', 'asc')->get();
	}

	public function validateRequest()
	{
		return request()->validate([
			'brand'=>'required|exists:brands,id',
			'season'=>'required|exists:seasons,id',
			'name_cn'=>'required',
			'name'=>'required',
			'category'=>'required|exists:categories,id',
			'color'=>'required|exists:colors,id',
		]);
	}
}

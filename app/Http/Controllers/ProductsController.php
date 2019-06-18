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
use Illuminate\Support\Arr;

class ProductsController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$query = Product::with(['images' => function ($query) {
			$query->orderBy('website_id', 'ASC')->orderBy('type_id', 'ASC');
		},'brand','prices']);
		$products = $this->filter($query)->get();
		$products = $this->sort($products);
		if ($request->input('show_available_only')) {
			$products = $products->filter(function ($item) {
				return $item->prices->isNotEmpty();
			});
		}
		$request->flash();
		return view('products.index', compact('products'));
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
		$product = Product::create($this->validateProduct());
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
		return view('products.show', compact('product'));
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
		$product->update($this->validateProduct());
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

	public function validateProduct()
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

	public function validateFilters()
	{
		return request()->validate([
			'category.*' => 'sometimes|exists:categories,id',
			'season.*' => 'sometimes|exists:seasons,id',
			'color.*' => 'sometimes|exists:colors,id',
			'brand.*' => 'sometimes|exists:brands,id',
		]);
	}

	public function filter($query)
	{
		$filters = $this->validateFilters();
		foreach ($filters as $field => $values) {
			$query->whereIn("{$field}_id", $values);
		}
		return $query;
	}

	public function sort($products)
	{
		if (request()->input('sort')) {
			$sort = request()->validate([
				'sort' => 'sometimes|exists:sortmethods,name',
			]);
		} else {
			$sort = 'default';
		}
		$products = $products->sortBy(function ($item) {
			return $item->category_id.$item->season_id.$item->id;
		});
		return $products;
	}
	// public function season_asc()
	// {
	// 	return function ($a, $b) {
	// 		return
	// 		($a->season_id == $b->season_id)?
	// 			($a->category_id == $b->category_id)?
	// 				$a->id - $b->id :
	// 			$a->category_id - $b->category_id :
	// 		$a->season_id - $b->season_id;
	// 	}
	// }
}

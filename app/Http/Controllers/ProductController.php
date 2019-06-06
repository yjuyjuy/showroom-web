<?php

namespace App\Http\Controllers;

use App\Category;
use App\Color;
use App\Product;
use App\Season;
use App\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$per_page = 50;

		// $products = Cache::remember(
		// 	"all.products.{$per_page}.page.".($request->input('page')??1),
		// 	now()->addMinutes(30),
		// 	function () use ($per_page) {
		// 		return Product::with(['brand','images' => function ($query) {
		// 			$query->orderBy('website_id', 'ASC')->orderBy('type_id', 'ASC');
		// 		}])->paginate($per_page);
		// 	}
		// );

		$products = Product::with(['brand','prices','images' => function ($query) {
			$query->orderBy('website_id', 'ASC')->orderBy('type_id', 'ASC');
		}])->paginate($per_page);

		$filters = [
			"category" => Category::all(),
			"color" => Color::all(),
			"season" => Season::all(),
			"brand" => Brand::all(),
		];
		return view('product.index', compact('products', 'filters'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('product.create');
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
		$product->load('images');
		return view('product.show', compact('product'));
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

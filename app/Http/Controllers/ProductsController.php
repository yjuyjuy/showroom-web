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
		$data = $request->validate([
			'show_available' => '',
			'category.*' => 'sometimes|exists:categories,id',
			'season.*' => 'sometimes|exists:seasons,id',
			'color.*' => 'sometimes|exists:colors,id',
			'brand.*' => 'sometimes|exists:brands,id',
			'sort' => 'sometimes|in:'.implode(',', Arr::pluck($this->sortoptions(), 'name')),
		]);
		$query = Product::with([
			'brand','prices','images' => function ($query) {
				$query->orderBy('website_id', 'ASC')->orderBy('type_id', 'ASC');
			}
		]);
		foreach (['category','color','brand','season'] as $field) {
			if ($request->input($field)) {
				$query->whereIn("{$field}_id", $data[$field]);
			}
		}
		$products = Product::sort_and_get($data['sort']??'default', $query);
		if (!empty($data['show_available'])) {
			$products = $products->filter(function ($product) {
				return $product->prices->isNotEmpty();
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
	public function sortoptions()
	{
		return [
			1 => ['name' => 'default','name_cn' => '默认排序',],
			2 => ['name' => 'price high to low','name_cn' => '价格最高',],
			3 => ['name' => 'price low to high','name_cn' => '价格最低'],
			4 => ['name' => 'hottest','name_cn' => '人气最高'],
			5 => ['name' => 'best selling','name_cn' => '销量最高'],
			6 => ['name' => 'newest','name_cn' => '最新到货'],
			7 => ['name' => 'oldest','name_cn' => '发布最早'],
		];
	}
}

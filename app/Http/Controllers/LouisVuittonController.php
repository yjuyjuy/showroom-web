<?php

namespace App\Http\Controllers;

use App\Product;
use App\LouisVuittonProduct;
use App\LouisVuittonImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class LouisVuittonController extends Controller
{
	public function index(Request $request, $category=null)
	{
		$categories = $this->getCategories();
		$query = LouisVuittonProduct::orderBy('id', 'desc');
		if ($category && in_array($category, $categories)) {
			$query->where('category', $category);
		} else {
			$category = NULL;
		}
		$total_pages = ceil($query->count() / 48.0);
		$page = min(max($request->query('page', 1), 1), $total_pages);
		$products = $query->forPage($page, 48)->get();

		$request->flash();
		return view('louisvuitton.index', compact('products', 'category', 'categories', 'page', 'total_pages'));
	}

	public function show(LouisVuittonProduct $product)
	{
		$product->load('images');
		return view('louisvuitton.show', compact('product'));
	}

	public function getCategories()
	{
		return Cache::remember('louisvuitton-categories', 60 * 60, function () {
			return LouisVuittonProduct::whereNotNull('category')->pluck('category')->unique()->toArray();
		});
	}

	public function export(LouisVuittonProduct $lv_product)
	{
		$product = Product::create([
			'brand_id' => $lv_product->brand_id,
			'designer_style_id' => $lv_product->id,
			'name_cn' => $lv_product->name,
			'name' => $lv_product->name,
			'category_id' => NULL,
			'id' => \App\Product::generate_id(),
		]);
		$lv_product->product_id = $product->id;
		$lv_product->save();
		(new ImageController())->import($lv_product->images, $product);
		return redirect(route('products.show', ['product' => $product,]));
	}

	public function merge(LouisVuittonProduct $lv_product, Product $product)
	{
		foreach ([
			'brand_id' => $lv_product->brand_id,
			'designer_style_id' => $lv_product->id,
			'name_cn' => $lv_product->name,
			'name' => $lv_product->name,
		] as $key => $value) {
			if (empty($product[$key])) {
				$product[$key] = $value;
			}
		}
		$product->save();
		$lv_product->product_id = $product->id;
		$lv_product->save();
		(new ImageController())->import($lv_product->images, $product);
		return redirect(route('products.show', ['product' => $product,]));
	}

	public function unlink(LouisVuittonProduct $lv_product)
	{
		$lv_product->product_id = NULL;
		$lv_product->save();

		return redirect(route('louisvuitton.show', ['product' => $lv_product]));
	}
}

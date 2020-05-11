<?php

namespace App\Http\Controllers;

use App\Product;
use App\GucciProduct;
use App\GucciImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class GucciController extends Controller
{
	public function index(Request $request, $category=null)
	{
		$categories = $this->getCategories();
		$query = GucciProduct::orderBy('id', 'desc');
		if ($category && in_array($category, $categories)) {
			$query->where('category', $category);
		} else {
			$category = null;
		}
		$total_pages = ceil($query->count() / 48.0);
		$page = min(max($request->query('page', 1), 1), $total_pages);
		$products = $query->forPage($page, 48)->get();

		$request->flash();
		return view('gucci.index', compact('products', 'category', 'categories', 'page', 'total_pages'));
	}

	public function show(GucciProduct $product)
	{
		$product->load('images');
		return view('gucci.show', compact('product'));
	}

	public function getCategories()
	{
		return Cache::remember('gucci-categories', 60 * 60, function () {
			return GucciProduct::whereNotNull('category')->orderBy('category')->pluck('category')->unique()->toArray();
		});
	}

	public function export(GucciProduct $gucci_product)
	{
		$product = Product::create([
			'brand_id' => $gucci_product->brand_id,
			'designer_style_id' => $gucci_product->id,
			'name_cn' => $gucci_product->name,
			'name' => $gucci_product->name,
			'category_id' => null,
			'id' => \App\Product::generate_id(),
		]);
		$gucci_product->product_id = $product->id;
		$gucci_product->save();
		(new ImageController())->import($gucci_product->images, $product);

		return redirect(route('products.show', ['product' => $product,]));
	}

	public function merge(GucciProduct $gucci_product, Product $product)
	{
		foreach ([
			'brand_id' => $gucci_product->brand_id,
			'designer_style_id' => $gucci_product->id,
			'name_cn' => $gucci_product->name,
			'name' => $gucci_product->name,
		] as $key => $value) {
			if (empty($product[$key])) {
				$product[$key] = $value;
			}
		}
		$product->save();
		$gucci_product->product_id = $product->id;
		$gucci_product->save();
		(new ImageController())->import($gucci_product->images, $product);

		return redirect(route('products.show', ['product' => $product,]));
	}

	public function unlink(GucciProduct $gucci_product)
	{
		$gucci_product->product_id = null;
		$gucci_product->save();

		return redirect(route('gucci.show', ['product' => $gucci_product]));
	}
}

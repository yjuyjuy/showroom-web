<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DiorController extends Controller
{
	public function index(Request $request, $token=null)
	{
		$categories = $this->getCategories();
		$query = DiorProduct::orderBy('id', 'desc');
		if (array_key_exists($token, $categories)) {
			$category = $categories[$token];
			$query->where('category', $category);
		} else {
			$category = NULL
		}
		$total_pages = ceil($query->count() / 48.0);
		$page = min(max($request->query('page', 1), 1), $total_pages);
		$products = $query->skip(($page - 1) * 48)->take(48)->get();

		$request->flash();
		return view('dior.index', compact('products', 'category', 'categories', 'sortOptions', 'filters', 'page', 'total_pages'));
	}

	public function show(DiorProduct $product)
	{
		$product->loadMissing(['images']);
		return view('dior.show', compact('product'));
	}

	public function getCategories()
	{
		return Cache::remember('dior-categories', 60 * 60, function () {
			$categories = [];
			foreach (DiorProduct::pluck('category')->unique() as $category) {
				$token = Str::slug($category);
				$categories[$token] = $category;
			}
			return $categories;
		});
	}

	public function export(DiorProduct $dior_product)
	{
		$product = Product::create([
			'brand_id' => $dior_product->brand_id,
			'designer_style_id' => $dior_product->id,
			'name_cn' => $dior_product->name_cn,
			'name' => $dior_product->name,
			'category_id' => NULL,
			'id' => \App\Product::generate_id(),
		]);
		$dior_product->product_id = $product->id;
		$dior_product->save();
		(new ImageController())->import($dior_product->images, $product);

		return redirect(route('products.show', ['product' => $product,]));
	}

	public function merge(DiorProduct $dior_product, Product $product)
	{
		foreach ([
			'brand_id' => $dior_product->brand_id,
			'designer_style_id' => $dior_product->id,
			'name_cn' => $dior_product->name_cn,
			'name' => $dior_product->name,
			'id' => \App\Product::generate_id(),
		] as $key => $value) {
			if (empty($product[$key])) {
				$product[$key] = $value;
			}
		}
		$product->save();
		$dior_product->product_id = $product->id;
		$dior_product->save();
		(new ImageController())->import($dior_product->images, $product);

		return redirect(route('products.show', ['product' => $product,]));
	}

	public function unlink(DiorProduct $dior_product)
	{
		$dior_product->product_id = NULL;
		$dior_product->save();

		return redirect(route('dior.show', ['product' => $dior_product]));
	}
}

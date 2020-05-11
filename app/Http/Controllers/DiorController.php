<?php

namespace App\Http\Controllers;

use App\Product;
use App\DiorProduct;
use App\DiorImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class DiorController extends Controller
{
	public function index(Request $request, $token=null)
	{
		$categories = $this->getCategories();
		$query = DiorProduct::orderBy('id', 'desc');
		if (array_key_exists($token, $categories)) {
			$query->where('category', $categories[$token]);
			$category = $token;
		} else {
			$category = null;
		}
		$total_pages = ceil($query->count() / 48.0);
		$page = min(max($request->query('page', 1), 1), $total_pages);
		$products = $query->forPage($page, 48)->get();

		$request->flash();
		return view('dior.index', compact('products', 'category', 'categories', 'page', 'total_pages'));
	}

	public function show(DiorProduct $product)
	{
		$product->load('images');
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
		$dior_product->product_id = null;
		$dior_product->save();

		return redirect(route('dior.show', ['product' => $dior_product]));
	}
}

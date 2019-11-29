<?php

namespace App\Http\Controllers;

use App\Product;
use App\BalenciagaProduct;
use App\BalenciagaImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class BalenciagaController extends Controller
{
	public function index(Request $request, $token=null)
	{
		$departments = $this->getDepartments();
		$query = BalenciagaProduct::orderBy('id', 'desc');
		if (in_array($token, $departments)) {
			$department = $token;
			$query->where('department', $department);
		} else {
			$department = NULL;
		}
		$total_pages = ceil($query->count() / 48.0);
		$page = min(max($request->query('page', 1), 1), $total_pages);
		$products = $query->forPage($page, 48)->get();

		$request->flash();
		return view('balenciaga.index', compact('products', 'department', 'departments', 'page', 'total_pages'));
	}

	public function show(BalenciagaProduct $product)
	{
		$product->load('images');
		return view('balenciaga.show', compact('product'));
	}

	public function getDepartments()
	{
		return Cache::remember('balenciaga-departments', 60 * 60, function () {
			return BalenciagaProduct::pluck('department')->unique()->toArray();
		});
	}

	public function export(BalenciagaProduct $balenciaga_product)
	{
		$product = Product::create([
			'brand_id' => $balenciaga_product->brand_id,
			'designer_style_id' => $balenciaga_product->designer_style_id,
			'name_cn' => $balenciaga_product->name,
			'name' => $balenciaga_product->name,
			'id' => \App\Product::generate_id(),
		]);
		$balenciaga_product->product_id = $product->id;
		$balenciaga_product->save();
		(new ImageController())->import($balenciaga_product->images, $product);

		return redirect(route('products.show', ['product' => $product,]));
	}

	public function merge(BalenciagaProduct $balenciaga_product, Product $product)
	{
		foreach ([
			'brand_id' => $balenciaga_product->brand_id,
			'designer_style_id' => $balenciaga_product->designer_style_id,
			'name_cn' => $balenciaga_product->name,
			'name' => $balenciaga_product->name,
		] as $key => $value) {
			if (empty($product[$key])) {
				$product[$key] = $value;
			}
		}
		$product->save();
		$balenciaga_product->product_id = $product->id;
		$balenciaga_product->save();
		(new ImageController())->import($balenciaga_product->images, $product);

		return redirect(route('products.show', ['product' => $product,]));
	}

	public function unlink(BalenciagaProduct $balenciaga_product)
	{
		$balenciaga_product->product_id = NULL;
		$balenciaga_product->save();

		return redirect(route('balenciaga.show', ['product' => $balenciaga_product]));
	}
}

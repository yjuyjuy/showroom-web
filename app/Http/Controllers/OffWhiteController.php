<?php

namespace App\Http\Controllers;

use App\Product;
use App\OffWhiteProduct;
use App\OffWhiteImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class OffWhiteController extends Controller
{
	public function index(Request $request, $token=null)
	{
		$categories = $this->getCategories();
		$sortOptions = $this->getSortOptions();
		$data = $request->validate([
			'category.*' => ['sometimes', Rule::in(array_keys($categories))],
			'sort' => ['sometimes', Rule::in($sortOptions)],
		]);
		$filters = [];
		$query = OffWhiteProduct::query();
		if (array_key_exists($token, $categories)) {
			$category = $token;
			$query->where('category', $categories[$token]);
		} else {
			$category = null;
			$filters['category'] = $categories;
			if (!empty($data['category'])) {
				$query->where(function ($query) use ($data, $categories) {
					foreach ($data['category'] as $token) {
						$query->orWhere('category', $categories[$token]);
					}
				});
			}
		}
		if (!empty($data['sort'])) {
			if ($data['sort'] == 'price-low-to-high') {
				$query->where('price', '>', 0)->orderBy('price');
			} elseif ($data['sort'] == 'price-high-to-low') {
				$query->where('price', '>', 0)->orderBy('price', 'desc');
			} else {
				$query->orderBy('id');
			}
		}

		$total_pages = ceil($query->count() / 48.0);
		$page = min(max($request->query('page', 1), 1), $total_pages);
		$products = $query->skip(($page - 1) * 48)->take(48)->get();

		$sortOptions = $this->getSortOptions();
		$request->flash();
		return view('offwhite.index', compact('products', 'category', 'categories', 'sortOptions', 'filters', 'page', 'total_pages'));
	}

	public function show(OffWhiteProduct $product)
	{
		$product->loadMissing(['images']);
		return view('offwhite.show', compact('product'));
	}

	public function categories()
	{
		$categories = $this->getCategories();
		return view('offwhite.categories', compact('categories'));
	}

	public function getCategories()
	{
		return Cache::remember('offwhite-categories', 60 * 60, function () {
			$categories = [];
			foreach (OffWhiteProduct::pluck('category')->unique() as $category) {
				$token = Str::slug($category);
				$categories[$token] = $category;
			}
			return $categories;
		});
	}

	public function getSortOptions()
	{
		return ['default', 'price-low-to-high', 'price-high-to-low'];
	}

	public static function export(OffWhiteProduct $offwhite_product, Product $product=null)
	{
		if ($product) {
			foreach ([
					'brand_id' => $offwhite_product->brand_id,
					'designer_style_id' => $offwhite_product->id,
					'name_cn' => $offwhite_product->name,
					'name' => $offwhite_product->name,
				] as $key => $value) {
				if (empty($product[$key])) {
					$product[$key] = $value;
				}
			}
			$product->save();
		} else {
			$product = Product::firstOrCreate([
					'brand_id' => $offwhite_product->brand_id,
					'designer_style_id' => $offwhite_product->id,
				], [
					'name_cn' => $offwhite_product->name,
					'name' => $offwhite_product->name,
					'id' => \App\Product::generate_id(),
				]);
		}
		// if ($offwhite_product->images->isNotEmpty()) {
		// 	ImageController::import($offwhite_product->images, $product, 1);
		// }
		return redirect(route('products.show', ['product' => $product,]));
	}
}

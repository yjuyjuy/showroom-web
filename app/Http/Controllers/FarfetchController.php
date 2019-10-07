<?php

namespace App\Http\Controllers;

use App\Product;
use App\FarfetchProduct;
use App\FarfetchDesigner;
use App\FarfetchCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class FarfetchController extends Controller
{
	public function index(Request $request, $token=null)
	{
		$categories = $this->getCategories();
		$designers = $this->getDesigners();
		$data = $request->validate([
			'designer.*' => ['sometimes', Rule::in($designers->pluck('id'))],
			'category.*' => ['sometimes', Rule::in($categories->pluck('id'))],
			'sort' => ['sometimes', Rule::in($this->getSortOptions())],
		]);
		$filters = [];
		$query = FarfetchProduct::with('designer', 'category');
		$designer = FarfetchDesigner::whereNotNull('url_token')->where('url_token', $token)->first();
		$category = FarfetchCategory::whereNotNull('url_token')->where('url_token', $token)->first();
		if ($designer) {
			$query->where('designer_id', $designer->id);
		} else {
			$filters['designer'] = FarfetchDesigner::all();
			if (!empty($data['designer'])) {
				$query->where(function ($query) use ($data) {
					foreach ($data['designer'] as $designer_id) {
						$query->orWhere('designer_id', $designer_id);
					}
				});
			}
		}
		if ($category) {
			$query->where('category_id', $category->id);
		} else {
			$filters['category'] = $categories;
			if (!empty($data['category'])) {
				$query->where(function ($query) use ($data) {
					foreach ($data['category'] as $category_id) {
						$query->orWhere('category_id', $category_id);
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
		return view('farfetch.index', compact('products', 'designer', 'designers', 'category', 'categories', 'sortOptions', 'filters', 'page', 'total_pages'));
	}

	public function show(FarfetchProduct $product)
	{
		$product->loadMissing(['category','designer','images']);
		return view('farfetch.show', compact('product'));
	}

	public function designers()
	{
		$designers = FarfetchDesigner::all();
		return view('farfetch.designers', compact('designers'));
	}

	public function getDesigners()
	{
		return Cache::remember('farfetch-designers', 60 * 60, function () {
			return FarfetchDesigner::has('products')->orderBy('description')->get();
		});
	}

	public function categories()
	{
		$categories = $this->getCategories();
		return view('farfetch.categories', compact('categories'));
	}

	public function getCategories()
	{
		return Cache::remember('farfetch-categories', 60 * 60, function () {
			return FarfetchCategory::has('products')->orderBy('description')->get();
		});
	}

	public function getSortOptions()
	{
		return ['default', 'price-low-to-high', 'price-high-to-low'];
	}

	public static function export(FarfetchProduct $farfetch_product, Product $product=null)
	{
		if ($product) {
			foreach([
				'brand_id' => $farfetch_product->designer->brand_id,
				'designer_style_id' => $farfetch_product->designer_style_id,
				'name_cn' => $farfetch_product->short_description,
				'name' => $farfetch_product->short_description,
			] as $key => $value) {
				if (empty($product[$key])) {
					$product[$key] = $value;
				}
			}
			$product->save();
		} else  {
			$product = Product::firstOrCreate([
				'brand_id' => $farfetch_product->designer->brand_id,
				'designer_style_id' => $farfetch_product->designer_style_id,
			], [
				'name_cn' => $farfetch_product->short_description,
				'name' => $farfetch_product->short_description,
				'id' => \App\Product::generate_id(),
			]);
		}
		if ($farfetch_product->images->isNotEmpty()) {
			ImageController::import($farfetch_product->images, $product, 2);
		}
		return redirect(route('products.edit', ['product' => $product,]));
	}
}

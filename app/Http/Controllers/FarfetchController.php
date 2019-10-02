<?php

namespace App\Http\Controllers;

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
		$data = $request->validate([
			'designer.*' => ['sometimes', 'exists:farfetch.designers,id'],
			'category.*' => ['sometimes', 'exists:farfetch.categories,id'],
			'sort' => ['sometimes', Rule::in(['default', 'price-low-to-high', 'price-high-to-low'])],
		]);
		$filters = [];
		$query = FarfetchProduct::with('designer', 'category');
		$designer = FarfetchDesigner::where('url_token', $token)->first();
		$category = FarfetchCategory::where('url_token', $token)->first();
		if ($designer) {
			$query->where('designer_id', $designer->id);
		} else {
			$filters['designer'] = FarfetchDesigner::all();
			if (array_key_exists('designer', $data) && $designers = $data['designer']) {
				$query->where(function ($query) use ($designers) {
					foreach ($designers as $designer_id) {
						$query->orWhere('designer_id', $designer_id);
					}
				});
			}
		}
		if ($category) {
			$query->where('category_id', $category->id);
		} else {
			$filters['category'] = FarfetchCategory::has('products')->get()->sortBy('description');
			if (array_key_exists('category', $data) && $categories = $data['category']) {
				$query->where(function ($query) use ($categories) {
					foreach ($categories as $category) {
						$query->orWhere('category_id', $category);
					}
				});
			}
		}
		if (array_key_exists('sort', $data) && $sort = $data['sort']) {
			if ($sort == 'price-low-to-high') {
				$query->where('price', '>', 0)->orderBy('price');
			} elseif ($sort == 'price-high-to-low') {
				$query->where('price', '>', 0)->orderBy('price', 'desc');
			} else {
				$query->orderBy('id');
			}
		}

		$total_pages = ceil($query->count() / 24.0);
		$page = min(max($request->query('page', 1), 1), $total_pages);
		$products = $query->skip(($page - 1) * 24)->take(24)->get();

		$sortOptions = ['default', 'price-low-to-high', 'price-high-to-low'];
		$request->flash();
		return view('farfetch.index', compact('products', 'designer', 'category', 'sortOptions', 'filters', 'page', 'total_pages'));
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

	public function categories()
	{
		$categories = Cache::remember('categories-has-products', 60 * 60, function() {
			return FarfetchCategory::has('products')->get();
		});
		return view('farfetch.categories', compact('categories'));
	}
}

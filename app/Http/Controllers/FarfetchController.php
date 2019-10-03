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
			'category.*' => ['sometimes', Rule::in($this->getCategories()->pluck('id'))],
			'sort' => ['sometimes', Rule::in($this->getSortOptions())],
		]);
		$filters = [];
		$query = FarfetchProduct::with('designer', 'category');
		$designer = FarfetchDesigner::where('url_token', $token)->first();
		$category = FarfetchCategory::where('url_token', $token)->first();
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
			$filters['category'] = $this->getCategories();
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

		$total_pages = ceil($query->count() / 24.0);
		$page = min(max($request->query('page', 1), 1), $total_pages);
		$products = $query->skip(($page - 1) * 24)->take(24)->get();

		$sortOptions = $this->getSortOptions();
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
}

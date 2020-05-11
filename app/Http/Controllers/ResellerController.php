<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ResellerController extends Controller
{
	public function index(Request $request)
	{
		$user = auth()->user();
		$query = \App\Product::has(['offers' => function ($query) {
			$query->whereIn('vendor_id', $user->following_resellers->pluck('id'));
		}]);

		$filters = $this->validateFilters();
		foreach ($filters as $field => $values) {
			$query->whereIn("{$field}_id", $values);
		}
		$sort = $request->input('sort');
		if (!$sort || !in_array($sort, $this->sortOptions())) {
			$sort = 'default';
		}
		if ($sort == 'default') {
			$query->orderBy('updated_at', 'desc');
		} elseif ($sort == 'newest') {
			$query->orderBy('season_id', 'desc')->orderBy('id');
		} elseif ($sort == 'oldest') {
			$query->orderBy('season_id')->orderBy('id');
		} elseif ($sort == 'random') {
			$query->inRandomOrder();
		} elseif ($sort == 'created_at') {
			$query->orderBy('created_at', 'desc');
		} elseif ($sort == 'category') {
			$query->orderBy('category_id')->orderBy('season_id', 'desc')->orderBy('id');
		}
		$products = $query->get();

		$products->load([
			'offers' => function ($query) use ($user) {
				$query->whereIn('vendor_id', $user->following_vendors->pluck('id'));
			},
		]);
		if ($sort == 'price-high-to-low') {
			$products = $products->sortByDesc(function ($item) {
				return $item->offer;
			})->values();
		} elseif ($sort == 'price-low-to-high') {
			$products = $products->sortBy(function ($item) {
				return $item->offer;
			})->values();
		}
		$total_pages = ceil($products->count() / 48.0);
		$page = min(max($request->query('page', 1), 1), $total_pages);
		$products = $products->forPage($page, 48);
		$products->load(['brand', 'image']);

		$sortOptions = $this->sortOptions();
		$filters = $this->filterOptions();
		$request->flash();
		return view('reseller.products.index', compact('products', 'sortOptions', 'filters', 'user', 'page', 'total_pages'));
	}

	public function validateFilters()
	{
		return request()->validate([
			'category.*' => 'sometimes|exists:categories,id',
			'season.*' => 'sometimes|exists:seasons,id',
			'color.*' => 'sometimes|exists:colors,id',
			'brand.*' => 'sometimes|exists:brands,id',
		]);
	}
	public function sortOptions()
	{
		return ['default', 'random','price-high-to-low','price-low-to-high','newest','oldest'];
	}
	public function filterOptions()
	{
		return [
			"brand" => \App\Brand::orderBy('name')->get(),
			"category" => \App\Category::all(),
			"color" => \App\Color::all(),
			"season" => \App\Season::all(),
		];
	}
}

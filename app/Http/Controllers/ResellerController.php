<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResellerController extends Controller
{
	public function index(Request $request)
	{
		$user = auth()->user();
		$query = \App\Product::query();
		$filters = $this->validateFilters();
		foreach ($filters as $field => $values) {
			$query->whereIn("{$field}_id", $values);
		}
		$sort = $request->input('sort');
		if (!$sort || !in_array($sort, $this->sortOptions())) {
			$sort = 'default';
		}
		if ($sort == 'default') {
			$query->orderBy('season_id', 'desc')->orderBy('category_id')->inRandomOrder();
		} elseif ($sort == 'newest') {
			$query->orderBy('season_id', 'desc')->inRandomOrder();
		} elseif ($sort == 'oldest') {
			$query->orderBy('season_id')->inRandomOrder();
		} elseif ($sort == 'random') {
			$query->inRandomOrder();
		}
		$products = $query->get();

		$products->load([
			'image', 'brand', 'offers' => function ($query) use ($user) {
				$query->whereIn('vendor_id', $user->following_vendors->pluck('id'));
			},
		]);
		$products = $products->filter(function($item) {
			return $item->offers->isNotEmpty();
		});
		if ($sort == 'price-high-to-low') {
			$products = $products->sortByDesc(function ($item) {
				return $item->getMinOffer(0);
			})->values();
		} elseif ($sort == 'price-low-to-high') {
			$products = $products->sortBy(function ($item) {
				return $item->getMinOffer(INF);
			})->values();
		}
		$total_pages = ceil($products->count() / 48.0);
		$page = min(max($request->query('page',1), 1), $total_pages);
		$products = $products->forPage($page, 48);
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
		return [ "category" => \App\Category::all(), "color" => \App\Color::all(), "season" => \App\Season::all(), "brand" => \App\Brand::all(), ];
	}
}

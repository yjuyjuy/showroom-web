<?php

namespace App\Http\Controllers\api\v3\customer;

use App\EndProduct;
use App\FarfetchProduct;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Retailer;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
	public function index(Request $request, $query = null)
	{
		// return Cache::remember(request()->fullUrl(), 1 * 60, function() use ($request) {
		$user = auth()->user();
		$ITEMS_PER_PAGE = 24;
		if (!$query) {
			if ($request->query('retailer') && $retailer = Retailer::where('name', $request->query('retailer'))->first()) {
				$query = $retailer->products();
			} else {
				$query = Product::whereHas('retails', function ($query) use ($user) {
					$query->whereIn('retailer_id', $user->following_retailers->pluck('id'));
				});
			}
		}
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
		} elseif ($sort == 'random') {
			$query->inRandomOrder();
		} elseif ($sort == 'created_at') {
			$query->orderBy('created_at', 'desc');
		}
		if ($request->input('show_available_only') || $sort == 'price-high-to-low' || $sort == 'price-low-to-high') {
			$query = $query->whereHas('retails', function($query) use ($user) {
				$query->whereIn('retailer_id', $user->following_retailers->pluck('id'));
			});
			$products = $query->get();

			if ($sort == 'price-high-to-low') {
				$products->load(['retails' => function($query) use ($user) {
					$query->whereIn('retailer_id', $user->following_retailers->pluck('id'));
				}]);
				$products = $products->sortByDesc(function ($product) {
					return $product->retail;
				})->values();
			}
			if ($sort == 'price-low-to-high') {
				$products->load(['retails' => function($query) use ($user) {
					$query->whereIn('retailer_id', $user->following_retailers->pluck('id'));
				}]);
				$products = $products->sortBy(function ($product) {
					return $product->retail;
				})->values();
			}
			$total_pages = ceil($products->count() / $ITEMS_PER_PAGE);
			$page = min(max(request()->query('page', 1), 1), $total_pages);
			$products = $products->forPage($page, $ITEMS_PER_PAGE);
		} else {
			$total_pages = ceil($query->count() / $ITEMS_PER_PAGE);
			$page = min(max(request()->query('page', 1), 1), $total_pages);
			$products = $query->forPage($page, $ITEMS_PER_PAGE)->get();
		}
		$products->loadMissing(['brand', 'images', 'season', 'retails' => function($query) use ($user) {
					$query->whereIn('retailer_id', $user->following_retailers->pluck('id'));
				}, 'retails.retailer']);
		return [
			'page' => $page,
			'total_pages' => $total_pages,
			'products' => $products->values(),
			'sort_options' => $this->sortOptions(),
			'filter_options' => $this->filterOptions(),
		];
		// });
	}

	public function follow(Request $request, Product $product)
	{
		$user = auth()->user();
		$user->following_products()->syncWithoutDetaching($product);
		return [
			'following_products' => $user->following_products()->pluck('product_id'),
		];
	}

	public function unfollow(Request $request, Product $product)
	{
		$user = auth()->user();
		$user->following_products()->detach($product);
		return [
			'following_products' => $user->following_products()->pluck('product_id'),
		];
	}

	public function following(Request $request)
	{
		$user = auth()->user();
		return $this->index($request, $user->following_products());
	}


	public function show(Product $product)
	{
		$user = auth()->user();
		return $product->load([
			'brand', 'season', 'color', 'category', 'measurement',
			'retails' =>  function ($query) use ($user) {
				$query->whereIn('retailer_id', $user->following_retailers->pluck('id'));
			}, 'retails.retailer', 'images',
		]);
	}

	public function validateFilters()
	{
		return (new \App\Http\Controllers\ProductController())->validateFilters();
	}

	public function sortOptions()
	{
		return (new \App\Http\Controllers\ProductController())->sortOptions();
	}

	public function filterOptions()
	{
		return (new \App\Http\Controllers\ProductController())->filterOptions();
	}
}

<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Vendor;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
	public function index(Request $request, $query = null)
	{
		// return Cache::remember(request()->fullUrl(), 1 * 60, function() use ($request) {
		$ITEMS_PER_PAGE = 24;
		if (!$query) {
			if ($request->query('vendor')) {
				$query = Vendor::findOrFail($request->query('vendor'))->products();
			} else {
				$query = Product::query();
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
			$query = $query->has('offers');
			$products = $query->get();

			if ($sort == 'price-high-to-low') {
				$products = $products->sortByDesc(function ($product) {
					return $product->offer;
				})->values();
			}
			if ($sort == 'price-low-to-high') {
				$products = $products->sortBy(function ($product) {
					return $product->offer;
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
		$products->loadMissing(['brand', 'images', 'season', 'offers']);
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
			'prices' => function ($query) use ($user) {
				$query->whereIn('vendor_id', $user->following_vendors()->pluck('vendor_id'));
			},
			'prices.vendor',
			'offers' => function ($query) use ($user) {
				$query->whereIn('vendor_id', $user->following_vendors()->pluck('vendor_id'));
			},
			'offers.vendor',
			'images' => function ($query) {
				$query->orderBy('order', 'asc');
			},
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

<?php

namespace App\Http\Controllers;

use App\Product;
use App\Retailer;
use Illuminate\Http\Request;

class RetailerController extends Controller
{
	public function index(Request $request, Retailer $retailer)
	{
		$user = auth()->user();
		$query = $retailer->products();

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
			'retails' => function ($query) use ($retailer) {
				$query->where('retailer_id', $retailer->id);
			},
		]);
		if ($sort == 'price-high-to-low') {
			$products = $products->sortByDesc(function ($item) {
				return $item->price;
			})->values();
		} elseif ($sort == 'price-low-to-high') {
			$products = $products->sortBy(function ($item) {
				return $item->price;
			})->values();
		}

		$total_pages = ceil($products->count() / 48.0);
		$page = min(max($request->query('page',1), 1), $total_pages);
		$products = $products->forPage($page, 48);
		$products->load(['brand', 'image']);

		$sortOptions = $this->sortOptions();
		$filters = $this->filterOptions();
		$request->flash();
		return view('retailer.products.index', compact('products', 'sortOptions', 'filters', 'user', 'retailer', 'page', 'total_pages'));
	}

	public function show(Retailer $retailer, Product $product)
	{
		$user = auth()->user();
		$product->load(['retails' => function($query) use ($retailer) {
			$query->where('retailer_id', $retailer->id);
		}]);
		if ($user) {
			if ($user->is_admin) {
				$product->load(['prices', 'prices.vendor']);
			} else if ($user->vendor) {
				$product->load([
					'prices' => function ($query) use ($user) {
						$query->where('vendor_id', $user->vendor_id);
					},
				]);
			} else if ($user->is_reseller) {
				$product->load([
					'offers' => function ($query) use ($user) {
						$query->whereIn('vendor_id', $user->following_vendors->pluck('id'));
					}, 'offers.vendor'
				]);
			}
		}
		$product->load(['images', 'brand','season','color']);
		return view('retailer.products.show', compact('product', 'retailer', 'user'));
	}

	public function following(Request $request)
	{
		$not_found = false;
		if ($request->input('search')) {
			$search = strtolower($request->validate([
				'search' => ['sometimes', 'string', 'max:255'],
			])['search']);
			$valid_tokens = \Illuminate\Support\Facades\Cache::remember('retailer-tokens', 60 * 60, function () {
				$tokens = [];
				foreach(\App\Retailer::all() as $retailer) {
					$tokens[strtolower($retailer->name)] = $retailer->id;
				}
				foreach(\App\User::has('vendor.retailer')->get() as $user) {
					$tokens[strtolower($user->wechat_id)] = $user->vendor->retailer->id;
					$tokens[strtolower($user->name)] = $user->vendor->retailer->id;
				}
				foreach(\App\Vendor::has('retailer')->get() as $vendor) {
					$tokens[strtolower($vendor->wechat_id)] = $vendor->retailer->id;
					$tokens[strtolower($vendor->name)] = $vendor->retailer->id;
				}
				return $tokens;
			});
			if (array_key_exists($search, $valid_tokens)) {
				return redirect(route('retailer.products.index', ['retailer' => Retailer::find($valid_tokens[$search]),]));
			} else {
				$not_found = true;
			}
			$request->flash();
		}
		$user = auth()->user();
		$retailers = $user->following_retailers ?? collect();
		return view('retailer.following', compact('retailers', 'not_found'));
	}

	public function validateFilters()
	{
		return (new ProductController())->validateFilters();
	}

	public function sortOptions()
	{
		return (new ProductController())->sortOptions();
	}

	public function filterOptions()
	{
		return (new ProductController())->filterOptions();
	}
}

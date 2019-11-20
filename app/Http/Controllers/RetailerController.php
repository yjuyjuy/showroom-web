<?php

namespace App\Http\Controllers;

use App\Product;
use App\Retailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class RetailerController extends ProductController
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
			$query->orderBy('category_id')->orderBy('season_id', 'desc')->orderBy('id');
		} elseif ($sort == 'newest') {
			$query->orderBy('season_id', 'desc')->orderBy('id');
		} elseif ($sort == 'oldest') {
			$query->orderBy('season_id')->orderBy('id');
		} elseif ($sort == 'random') {
			$query->inRandomOrder();
		}
		$products = $query->get();

		$products->load([
			'image', 'brand', 'retails' => function ($query) use ($retailer) {
				$query->where('retailer_id', $retailer->id);
			},
		]);
		if ($sort == 'price-high-to-low') {
			$products = $products->sortByDesc(function ($item) {
				return $item->getMinPrice(0);
			})->values();
		} elseif ($sort == 'price-low-to-high') {
			$products = $products->sortBy(function ($item) {
				return $item->getMinPrice(INF);
			})->values();
		}
		$total_pages = ceil($products->count() / 24.0);
		$page = min(max($request->query('page',1), 1), $total_pages);
		$products = $products->forPage($page, 24);
		$sortOptions = $this->sortOptions();
		$filters = $this->filterOptions();
		$request->flash();
		return view('retailer.products.index', compact('products', 'sortOptions', 'filters', 'user', 'retailer', 'page', 'total_pages'));
	}

	public function show(Retailer $retailer, Product $product)
	{
		$user = auth()->user();
		$product->load(['images', 'retails' => function ($query) use ($retailer) {
			$query->where('retailer_id', $retailer->id);
		}]);
		return view('retailer.products.show', compact('product', 'retailer', 'user'));
	}

	public function following(Request $request)
	{
		$not_found = false;
		if ($request->input('search')) {
			$search = $request->validate([
				'search' => ['sometimes', 'string', 'max:255'],
			])['search'];
			$valid_tokens = [];
			foreach(Retailer::all() as $retailer) {
				$valid_tokens[$retailer->name] = $retailer->id;
			}
			foreach(User::has('vendor.retailer')->get() as $user) {
				$valid_tokens[$user->wechat_id] = $user->vendor->retailer->id;
				$valid_tokens[$user->name] = $user->vendor->retailer->id;
			}
			foreach(Vendor::has('retailer')->get() as $vendor) {
				$valid_tokens[$vendor->wechat_id] = $vendor->retailer->id;
				$valid_tokens[$vendor->name] = $vendor->retailer->id;
			}
			if (array_key_exists($search, $valid_tokens)) {
				return redirect(route('retailer.products.index', ['retailer' => Retailer::find($valid_tokens[$search]),]));
			} else {
				$not_found = true;
			}
			$request->flash();
		}
		$user = auth()->user();
		$retailers = $user->following_retailers;
		return view('retailer.following', compact('retailers', 'not_found'));
	}
}

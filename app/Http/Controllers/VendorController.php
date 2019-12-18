<?php

namespace App\Http\Controllers;

use App\Vendor;
use App\Product;
use Illuminate\Http\Request;

class VendorController extends Controller
{
	public function index(Request $request, Vendor $vendor)
	{
		$user = auth()->user();
		if (!$user->following_vendors->contains($vendor)) {
			$user->following_vendors()->syncWithoutDetaching($vendor);
			$user->refresh();
		}
		$query = $vendor->products();

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
			$query->orderBy('season_id', 'desc');
		} elseif ($sort == 'oldest') {
			$query->orderBy('season_id')->orderBy('id');
		} elseif ($sort == 'random') {
			$query->inRandomOrder();
		} elseif ($sort == 'created_at') {
			$query->orderBy('created_at', 'desc')->orderBy('id');
		} elseif ($sort == 'category') {
			$query->orderBy('category_id')->orderBy('season_id', 'desc')->orderBy('id');
		}
		$products = $query->get();

		$products->load([
			'offers' => function ($query) use ($vendor) {
				$query->where('vendor_id', $vendor->id);
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
		$total_pages = ceil($products->count() / 48.0);
		$page = min(max($request->query('page', 1), 1), $total_pages);
		$products = $products->forPage($page, 48);
		$products->load(['brand', 'image']);

		$sortOptions = $this->sortOptions();
		$filters = $this->filterOptions();
		$request->flash();
		return view('vendor.products.index', compact('products', 'sortOptions', 'filters', 'user', 'vendor', 'page', 'total_pages'));
	}

	public function show(Vendor $vendor, Product $product)
	{
		$user = auth()->user();

		if (!$user || !$user->can('view', $vendor)) {
			return redirect(route('following.vendors'));
		}
		if ($user) {
			if ($user->isSuperAdmin()) {
				$product->load(['prices', 'prices.vendor']);
			}
			if ($user->is_reseller) {
				$vendor_ids = $user->following_vendors->pluck('id');
				if (!$vendor_ids->contains($vendor->id)) {
					$vendor_ids->prepend($vendor->id);
				}
				$product->load([
					'offers' => function ($query) use ($vendor_ids) {
						$query->whereIn('vendor_id', $vendor_ids);
					}, 'offers.vendor'
				]);
			}
			$product->load([
				'retails' => function ($query) use ($user) {
					$query->whereIn('retailer_id', $user->following_retailers->pluck('id'));
				}, 'retails.retailer'
			]);
		} else {
			$product->load([
				'retails' => function ($query) use ($user) {
					$query->whereIn('retailer_id', $user->following_retailers->pluck('id'));
				}, 'retails.retailer'
			]);
			$product->offers = collect();
		}
		return view('vendor.products.show', compact('product', 'vendor', 'user'));
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

	public function following(Request $request)
	{
		$message = false;

		if ($request->input('search')) {
			$search = $request->validate([
				'search' => ['sometimes', 'string', 'max:255'],
				])['search'];
			$valid_tokens = [];
			foreach(\App\User::has('vendor')->whereNotNull('wechat_id')->get() as $user) {
				$valid_tokens[$user->wechat_id] = $user->vendor_id;
			}
			foreach(Vendor::whereNotNull('wechat_id')->get() as $vendor) {
				$valid_tokens[$vendor->wechat_id] = $vendor->id;
			}
			if (array_key_exists($search, $valid_tokens)) {
				$vendor = \App\Vendor::find($valid_tokens[$search]);
				$user = auth()->user();
				if ($user->following_vendors->contains($vendor)) {
					$message = '已经关注"'.$vendor->name.'"了';
				} else {
					$user->following_vendors()->attach($vendor);
					$message = '成功添加关注"'.$vendor->name.'"';
				}
			} else {
				$message = '没有找到微信号是"'.$search.'"的同行';
			}
		}
		$user = auth()->user();
		$vendors = $user->fresh()->following_vendors;
		return view('vendor.following', compact('vendors', 'message'));
	}
}

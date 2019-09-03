<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vendor;
use App\Product;

class VendorController extends Controller
{
	public function index(Request $request, Vendor $vendor)
	{
		$this->authorize('view', $vendor);
		$user = auth()->user();
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
			$query->orderBy('category_id')->orderBy('season_id', 'desc')->orderBy('category_id')->inRandomOrder();
		} elseif ($sort == 'newest') {
			$query->orderBy('season_id', 'desc')->inRandomOrder();
		} elseif ($sort == 'oldest') {
			$query->orderBy('season_id')->inRandomOrder();
		} elseif ($sort == 'random') {
			$query->inRandomOrder();
		}
		$products = $query->get();

		$products->load([
			'image', 'brand', 'offers' => function ($query) use ($vendor) {
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
		$total_pages = ceil($products->count() / 24.0);
		$page = min(max($request->query('page', 1), 1), $total_pages);
		$products = $products->forPage($page, 24);
		$sortOptions = $this->sortOptions();
		$filters = $this->filterOptions();
		$request->flash();
		return view('vendor.products.index', compact('products', 'sortOptions', 'filters', 'user', 'vendor', 'page', 'total_pages'));
	}

	public function show(Vendor $vendor, Product $product)
	{
		$this->authorize('view', $vendor);
		$user = auth()->user();
		$product->load(['images', 'offers' => function ($query) use ($vendor) {
			$query->where('vendor_id', $vendor->id);
		}]);
		return view('vendor.products.show', compact('product', 'vendor', 'user'));
	}

	// public function edit(Vendor $vendor)
	// {
	// 	$this->authorize('view', $vendor);
	// 	$vendor = $user->vendors()->find($vendor->id);
	// 	return view('vendor.following.edit', compact('vendor'));
	// }
	//
	// public function update(Vendor $vendor)
	// {
	// 	$this->authorize('view', $vendor);
	// 	$user = auth()->user();
	// 	$profit_rate = $this->validate([
	// 		'profit_rate' => ['required', 'number', 'min:'.($vendor->min_profit_rate ?? 5.0), 'max:100'],
	// 	])['profit_rate'];
	// 	$user->following_vendors()->updateExistingPivot($vendor->id, compact('profit_rate'));
	// 	return redirect(route('vendor.products.index', compact('vendor')));
	// }

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
			"category" => \App\Category::all(),
			"color" => \App\Color::all(),
			"season" => \App\Season::all(),
			"brand" => \App\Brand::all()
		];
	}

	public function following(Request $request)
	{
		$user = auth()->user();
		$message = false;

		if ($request->input('wechat_id')) {
			$wechat_id = $request->validate([
					'wechat_id' => ['sometimes', 'string', 'max:255'],
				])['wechat_id'];
			$vendor = \App\Vendor::where('wechat_id', $wechat_id)->first();
			if (!$vendor) {
				$message = '没有找到微信号是"'.$wechat_id.'"的同行';
			} elseif ($user->following_vendors->contains($vendor)) {
				$message = '已经关注"'.$vendor->name.'"了';
			} else {
				$user->following_vendors()->attach($vendor);
				$message = '成功添加关注"'.$vendor->name.'"';
			}
		}
		$vendors = $user->fresh()->following_vendors;
		return view('vendor.following', compact('vendors', 'message'));
	}
}

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
		return view('vendor.products.index', compact('products', 'sortOptions', 'filters', 'user', 'vendor', 'page', 'total_pages'));
	}

	public function show(Vendor $vendor, Product $product)
	{
		$user = auth()->user();
		$product->load([
			'offers' => function ($query) use ($vendor) {
				$query->where('vendor_id', $vendor->id);
			}, 'offers.vendor'
		]);
		if ($user->is_admin) {
			$product->load(['prices', 'prices.vendor']);
		} elseif ($user->vendor) {
			$product->load([
				'prices' => function ($query) use ($user) {
					$query->where('vendor_id', $user->vendor_id);
				},
			]);
		}
		$product->load(['images', 'brand','season','color', 'category']);
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

		if ($name = $request->input('search')) {
			$vendor = Vendor::where('name', $name)->first();
			if ($vendor) {
				return redirect()->route('vendor.products.index', ['vendor' => $vendor,]);
			} else {
				$message = '没有找到微信号是"'.$search.'"的同行';
			}
		}
		$user = auth()->user();
		$vendors = $user->fresh()->following_vendors;
		return view('vendor.following', compact('vendors', 'message'));
	}

	public function create()
	{
		$name = 'Donkeys';
		$city = '上海';
		$wechat_id = 'mel_donkeys';

		$retailer = new App\Retailer();
		$retailer->id = random_int(1000000000, 10000000000);
		$retailer->name = $name;
		$retailer->homepage = null;
		$retailer->save();

		$vendor = new App\Vendor();
		$vendor->id = random_int(1000000000, 10000000000);
		$vendor->name = $name;
		$vendor->wechat_id = $wechat_id;
		$vendor->city = $city;
		$vendor->retailer_id = $retailer->id;
		$vendor->save();
	}
	public function follow(Vendor $vendor)
	{
		$user = auth()->user();
		return [
			'success' => $user->following_vendors()->syncWithoutDetaching($vendor),
		];
	}
	public function unfollow(Vendor $vendor)
	{
		$user = auth()->user();
		return [
			'success' => $user->following_vendors()->detach($vendor),
		];
	}
}

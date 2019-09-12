<?php

namespace App\Http\Controllers;

use App\Category;
use App\Color;
use App\Product;
use App\Season;
use App\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
	public function index(Request $request, $query=false)
	{
		$user = auth()->user();
		$admin = ($user) ? $user->isSuperAdmin() : false;
		$vendor = $user->vendor ?? false;
		if (!$query) {
			# vendor filters
			if ($vendor && $request->input('show_my_stock_only')) {
				$query = $vendor->products();
			} else {
				$query = \App\Product::query();
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
			$query->orderBy('category_id')->orderBy('season_id', 'desc')->orderBy('id');
		} elseif ($sort == 'newest') {
			$query->orderBy('season_id', 'desc')->orderBy('id');
		} elseif ($sort == 'oldest') {
			$query->orderBy('season_id')->orderBy('id');
		} elseif ($sort == 'random') {
			$query->inRandomOrder();
		}

		$products = $query->get();
		if ($user) {
			$retailer_ids = $user->following_retailers()->pluck('retailer_id');
		} else {
			$retailer_ids = [2471873538];
		}

		$products->load([
			'retails' => function ($query) use ($retailer_ids) {
				$query->whereIn('retailer_id', $retailer_ids);
			},
		]);
		if ($user) {
			if ($request->input('show_available_only')) {
				$products = $products->filter(function ($item) {
					return $item->retails->isNotEmpty();
				});
			}
		} else {
			$products = $products->filter(function ($item) {
				return $item->retails->isNotEmpty();
			});
		}
		if ($sort == 'price-high-to-low') {
			$products = $products->sortByDesc(function ($item) {
				return $item->getMinPrice(0);
			})->values();
		}
		if ($sort == 'price-low-to-high') {
			$products = $products->sortBy(function ($item) {
				return $item->getMinPrice(INF);
			})->values();
		}
		$total_pages = ceil($products->count() / 24.0);
		$page = min(max($request->query('page', 1), 1), $total_pages);
		$products = $products->forPage($page, 24);
		$products->load(['brand', 'image']);
		$sortOptions = $this->sortOptions();
		$filters = $this->filterOptions();
		$request->flash();
		return view('products.index', compact('products', 'sortOptions', 'filters', 'user', 'page', 'total_pages'));
	}

	public function following(Request $request)
	{
		$user = auth()->user();
		return $this->index($request, $user->following_products());
	}

	public function create()
	{
		$product = new Product();
		return view('products.create', compact('product'));
	}

	public function store(Request $request)
	{
		$product = new Product($this->validateProduct());
		$product->id = random_int(1000000000, 9999999999);
		while (Product::find($product->id)) {
			$product->id = random_int(1000000000, 9999999999);
		}
		$product->save();
		return redirect(route('images.edit', ['product' => $product]));
	}

	public function show(Product $product)
	{
		$user = auth()->user();
		if ($user->isSuperAdmin()) {
			$product->load(['prices.vendor']);
		}
		if ($user->is_reseller) {
			$product->load([
				'offers' => function ($query) use ($user) {
					$query->whereIn('vendor_id', $user->following_vendors->pluck('id'));
				}, 'offers.vendor', ]);
		}
		$product->loadMissing([
			'retails' => function ($query) use ($user) {
				$query->whereIn('retailer_id', $user->following_retailers->pluck('id'));
			}, 'retails.retailer', 'images', 'brand','season','color'
		]);
		return view('products.show', compact('product', 'user'));
	}

	public function random()
	{
		$user = auth()->user()->load('following_retailers');
		while (true) {
			$product = \App\Product::inRandomOrder()->first();
			$product->load(['retails' => function ($query) use ($user) {
				$query->whereIn('retailer_id', $user->following_retailers->pluck('id'));
			}]);
			if ($product->retails->isNotEmpty()) {
				return $this->show($product);
			}
		}
	}

	public function edit(Product $product)
	{
		$product->load(['images' => function ($query) {
			$query->orderBy('website_id')->orderBy('order');
		}]);
		return view('products.edit', compact('product'));
	}

	public function update(Request $request, Product $product)
	{
		$product->update($this->validateProduct());
		return redirect(route('products.show', ['product' => $product]));
	}

	public function destroy(Product $product)
	{
		$product->delete();
		return redirect(route('products.index'));
	}

	public function validateProduct()
	{
		return request()->validate([
			'brand' => ['required', 'exists:brands,id',],
			'season' => ['required', 'exists:seasons,id',],
			'name' => ['nullable', 'string', 'max:255',],
			'name_cn' => ['required', 'string', 'max:255',],
			'category' => ['required', 'exists:categories,id',],
			'color' => ['required', 'exists:colors,id',],
			'designerStyleId' => ['nullable','string','max:255',],
			'comment' => ['nullable','string','max:255',],
		]);
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
			"category" => \App\Category::all(),
			"color" => \App\Color::all(),
			"season" => \App\Season::all(),
			"brand" => \App\Brand::all()
		];
	}
}

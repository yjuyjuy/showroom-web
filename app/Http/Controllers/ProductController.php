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
		} elseif ($sort == 'created_at') {
			$query->orderBy('created_at', 'desc')->orderBy('id');
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
		$total_pages = ceil($products->count() / 48.0);
		$page = min(max($request->query('page', 1), 1), $total_pages);
		$products = $products->forPage($page, 48);
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

	public function init(Product $product, $url)
	{
		if (strpos($url, 'farfetch')) {
			# farfetch
			if (preg_match('#notdopebxtch.com/farfetch/([0-9]+)#', $url, $results) || preg_match('#www\.farfetch.*?item-([0-9]+)\.aspx#', $url, $results))
			{
				if ($farfetch_product = \App\FarfetchProduct::find($results[1])) {
					FarfetchController::export($farfetch_product, $product);
				}
			}
		} elseif (strpos($url, 'end')) {
			# end
			if (preg_match('#notdopebxtch.com/end/[0-9]+#', $url, $results)) {
				$end_product = \App\EndProduct::find($results[1]);
			} else if (preg_match('#www\.endclothing\.com/[a-zA-Z]+/([a-zA-Z0-9-]+)\.html#', $url, $results)) {
				$end_product = \App\EndProduct::where('url', "https://www.endclothing.com/cn/{$results[1]}.html")->first();
			}
			if ($end_product ?? false) {
				EndController::export($end_product, $product);
			}
		} elseif (preg_match('#www\.notdopebxtch\.com/off-white/([A-Z0-9]+)#', $url, $results) || preg_match('#www.off---white.com/.*/products/([a-zA-Z]+)#', $url, $results)) {
			# off-white
			if ($offwhite_product = \App\OffWhiteProduct::find(strtoupper($results[1]))) {
				OffWhiteController::export($offwhite_product);
			}
		}
	}

	public function store(Request $request)
	{
		$product = new Product($this->validateProduct());
		$product->id = Product::generate_id();
		$product->save();
		if($url = $request->input('url')) {
			$this->init($product, $url);
		}
		return redirect(route('products.edit', ['product' => $product]));
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
		if($url = $request->input('url')) {
			$this->init($product, $url);
		}
		return redirect(route('products.show', ['product' => $product]));
	}

	public function destroy(Product $product)
	{
		$product->offers()->delete();
		$product->retails()->delete();
		$product->prices()->delete();
		$product->taobao_prices()->update(['product_id' => null,]);
		$product->delete();
		return redirect(route('products.index'));
	}

	public function validateProduct()
	{
		return request()->validate([
			'brand' => ['nullable', 'exists:brands,id',],
			'season' => ['nullable', 'exists:seasons,id',],
			'name' => ['nullable', 'string', 'max:255',],
			'name_cn' => ['nullable', 'string', 'max:255',],
			'category' => ['nullable', 'exists:categories,id',],
			'color' => ['nullable', 'exists:colors,id',],
			'designer_style_id' => ['nullable','string','max:255',],
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
		return ['default', 'random','price-high-to-low','price-low-to-high','newest','oldest','created_at'];
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

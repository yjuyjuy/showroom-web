<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
	public function index(Request $request, $query=false)
	{
		$user = auth()->user();
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

		if ($user) {
			$retailer_ids = $user->following_retailers()->pluck('retailer_id');
		} else {
			$retailer_ids = [];
		}

		$products->load([
			'retails' => function ($query) use ($retailer_ids) {
				$query->whereIn('retailer_id', $retailer_ids);
			},
		]);
		if ($request->input('show_available_only')) {
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

	public function import(Product $product, $url)
	{
		if (strpos($url, 'farfetch') !== false) {
			# farfetch
			if (preg_match('#notdopebxtch.com/farfetch/([0-9]+)#', $url, $results) || preg_match('#www\.farfetch.*?item-([0-9]+)\.aspx#', $url, $results) || preg_match('#^farfetch-([0-9]+)$#', $url, $results)) {
				if ($farfetch_product = \App\FarfetchProduct::find($results[1])) {
					(new FarfetchController())->merge($farfetch_product, $product);
				} else {
					\App\FarfetchProduct::create(['id' => $results[1]]);
				}
			} else {
			}
		} elseif (strpos($url, 'end') !== false) {
			# end
			if (preg_match('#notdopebxtch.com/end/[0-9]+#', $url, $results) ||
			preg_match('#^end-([0-9]+)$#', $url, $results)) {
				$end_product = \App\EndProduct::find($results[1]);
			} elseif (preg_match('#www\.endclothing\.com/[a-zA-Z]+/([^/]+)\.html#', $url, $results)) {
				$end_product = \App\EndProduct::where('url', "https://www.endclothing.com/cn/{$results[1]}.html")->first();
			}
			if ($end_product ?? false) {
				(new EndController())->export($end_product, $product);
			}
		} elseif (strpos($url, 'off') !== false && strpos($url, 'white') !== false) {
			if (preg_match('#www\.notdopebxtch\.com/off-white/([^/]+)#', $url, $results) || preg_match('#www.off---white.com/.*/products/([^/]+)#', $url, $results)|| preg_match('#^offwhite-([0-9A-Za-z]+)$#', $url, $results)) {
				# off-white
				if ($offwhite_product = \App\OffWhiteProduct::find(strtoupper($results[1]))) {
					(new OffWhiteController())->export($offwhite_product);
				}
			}
		}
	}

	public function store(Request $request)
	{
		$product = new Product($this->validateProduct());
		$product->id = Product::generate_id();
		$product->save();
		if ($url = $request->input('url')) {
			$this->import($product, $url);
		}
		return redirect(route('products.edit', ['product' => $product]));
	}

	public function show(Product $product)
	{
		$user = auth()->user();
		if ($user) {
			if ($user->isSuperAdmin()) {
				$product->load(['prices', 'prices.vendor']);
			}
			if ($user->is_reseller) {
				$product->load([
					'offers' => function ($query) use ($user) {
						$query->whereIn('vendor_id', $user->following_vendors->pluck('id'));
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
		$product->load(['images', 'brand','season','color']);
		return view('products.show', compact('product', 'user'));
	}

	public function random()
	{
		return redirect(route('products.show', ['product' => \App\Product::inRandomOrder()->first()]));
	}

	public function edit(Product $product)
	{
		$product->load(['images']);
		return view('products.edit', compact('product'));
	}

	public function update(Request $request, Product $product)
	{
		$product->update($this->validateProduct());
		if ($url = $request->input('url')) {
			$this->import($product, $url);
		}
		return ['success' => true,];
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
			'brand' => ['exists:brands,id',],
			'season' => ['exists:seasons,id',],
			'name' => ['string', 'max:255',],
			'name_cn' => ['string', 'max:255',],
			'category' => ['exists:categories,id',],
			'color' => ['exists:colors,id',],
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
		return ['default', 'random','price-high-to-low','price-low-to-high','newest','oldest','created_at','category'];
	}

	public function filterOptions()
	{
		return [
			"brand" => \App\Brand::orderBy('name')->get(),
			"season" => \App\Season::all(),
			"category" => \App\Category::all(),
			"color" => \App\Color::all(),
		];
	}
}

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
	public function index(Request $request)
	{
		$user = auth()->user();
		$products = $this->filter(Product::query());
		$products = $this->sort($products);

		if ($request->input('sort') === 'random') {
			$products = $products->shuffle();
		}

		$sortOptions = $this->sortOptions();
		$filters = $this->filterOptions();
		$request->flash();
		return view('products.index', compact('products', 'sortOptions', 'filters', 'user'));
	}

	public function following()
	{
		$user = auth()->user();
		$products = $this->filter($user->following_products());
		$products = $this->sort($products);
		$sortOptions = $this->sortOptions();
		$filters = $this->filterOptions();
		return view('products.index', compact('products', 'sortOptions', 'filters', 'user'));
	}

	public function create()
	{
		$product = new Product();
		return view('products.create', compact('product'));
	}

	public function store(Request $request)
	{
		// TODO: think of a better way to generate unique product id for new product
		$product = new Product($this->validateProduct());
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
			$query->orderBy('website_id', 'ASC')->orderBy('type_id', 'ASC');
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
			'brand'=>'required|exists:brands,id',
			'season'=>'required|exists:seasons,id',
			'name' => ['required', 'string', 'max:255'],
			'name_cn' => ['required', 'string', 'max:255'],
			'category'=>'required|exists:categories,id',
			'color'=>'required|exists:colors,id',
			'designerStyleId'=>['nullable','string','max:255'],
			'comment' => ['nullable','string','max:255'],
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

	public function filter($query)
	{
		$request = request();
		$filters = $this->validateFilters();
		foreach ($filters as $field => $values) {
			$query->whereIn("{$field}_id", $values);
		}
		$products = $query->get();
		$user = auth()->user();
		if ($user) {
			$retailer_ids = $user->following_retailers->pluck('id');
		} else {
			$retailer_ids = [1];
		}
		$products->load(['retails' => function ($query) use ($retailer_ids) {
			$query->whereIn('retailer_id', $retailer_ids);
		}, 'image', 'brand']);

		if ($request->input('show_available_only')) {
			$products = $products->filter(function ($item) {
				return $item->retails->isNotEmpty();
			});
		}

		# admin filters
		if ($user && ($user->isSuperAdmin())) {
			if ($request->input('show_empty_only')) {
				$products = $products->filter(function ($item) {
					return $item->image;
				});
			} elseif ($request->input('show_not_empty_only')) {
				$products = $products->filter(function ($item) {
					return $item->image;
				});
			}
			if ($vendors = $request->input('vendor')) {
				$products->load(['offers' => function ($query) use ($vendors) {
					foreach ($vendors as $vendor) {
						$query->orWhere('vendor_id', $vendor);
					}
				}]);
				$products = $products->filter(function ($item) {
					return $item->offers->isNotEmpty();
				});
			}
		} else {
			$products = $products->filter(function ($item) {
				return $item->image;
			});
		}

		# vendor filters
		if ($user && ($vendor = $user->vendor) && $request->input('show_my_stock_only')) {
			$products->load(['prices' => function ($query) {
				$query->where('vendor_id', $vendor->id);
			}]);
			$products = $products->filter(function ($item) {
				return $item->prices->isNotEmpty();
			});
		}
		return $products;
	}

	public function sortOptions()
	{
		return ['default', 'random','price-high-to-low','price-low-to-high','hottest','best-selling','newest','oldest'];
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
	public function sort($products)
	{
		if (request()->input('sort')) {
			$sort = request()->validate([
				'sort' => ['sometimes',Rule::in($this->sortOptions())],
			])['sort'];
		} else {
			$sort = 'default';
		}
		$products = $products->shuffle();
		switch ($sort) {
			case 'default':
				$products = $products->sortBy(function ($item) {
					return $item->category_id.(999-$item->season_id).$item->id;
				});
				break;

			case 'random':
				$products = $products->shuffle();
				break;

			case 'price-high-to-low':
				$products = $products->sortByDesc(function ($item) {
					return $item->getMinPrice(0);
				});
				break;

			case 'price-low-to-high':
				$products = $products->sortBy(function ($item) {
					return $item->getMinPrice(INF);
				});
				break;

			case 'newest':
				$products = $products->sortByDesc(function ($item) {
					return $item->season_id;
				});
				break;

			case 'oldest':
				$products = $products->sortBy(function ($item) {
					return $item->season_id;
				});
				break;

			case 'best selling':
			case 'hottest':
			default:
				$products = $products->sortBy(function ($item) {
					return $item->category_id.(999-$item->season_id).$item->id;
				});
				break;
		}
		return $products;
	}
	public function follow(Product $product)
	{
		$user = auth()->user();
		return $user->following_products()->syncWithoutDetaching($product);
	}
	public function unfollow(Product $product)
	{
		$user = auth()->user();
		return $user->following_products()->detach($product);
	}
}

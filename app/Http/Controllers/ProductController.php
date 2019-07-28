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
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$products = Cache::remember(url()->full(), 60, function () use ($request) {
			$products = $this->filter(Product::query())->get();
			$products = $this->sort($products);
			$products->load(['images' => function ($query) {
				$query->orderBy('website_id', 'ASC')->orderBy('type_id', 'ASC');
			},'brand','prices']);
			if ($request->input('show_available_only')) {
				$products = $products->filter(function ($item) {
					return $item->prices->isNotEmpty();
				});
			}
			if (($user = auth()->user()) && ($user->isSuperAdmin())) {
				if ($request->input('show_empty_only')) {
					$products = $products->filter(function ($item) {
						return $item->images->isEmpty();
					});
				} elseif ($request->input('show_not_empty_only')) {
					$products = $products->filter(function ($item) {
						return $item->images->isNotEmpty();
					});
				}
			} else {
				$products = $products->filter(function ($item) {
					return $item->images->isNotEmpty();
				});
			}

			// if (($user = auth()->user()) && ($vendor = $user->vendor)) {
			// 	if ($request->input('show_vendor_only')) {
			// 		$products = $products->filter(function ($item) use ($vendor) {
			// 			return $item->prices->firstWhere('vendor_id', $vendor->id);
			// 		});
			// 	}
			// 	if ($user->isSuperAdmin() && ($vendors = $request->input('vendor'))) {
			// 		$products = $products->filter(function ($item) use ($vendors) {
			// 			return $item->prices->whereIn('vendor_id', $vendors)->first();
			// 		});
			// 	}
			// }
			return $products;
		});
		if ($request->input('sort') === 'random') {
			$products = $products->shuffle();
		}
		$sortOptions = $this->sortOptions();
		$filters = [
			"category" => \App\Category::all(),
			"color" => \App\Color::all(),
			"season" => \App\Season::all(),
			"brand" => \App\Brand::all()
			];
		$request->flash();
		return view('products.index', compact('products', 'sortOptions', 'filters'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$product = new Product();
		return view('products.create', compact('product'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$product = new Product($this->validateProduct());
		$lastProduct = Product::where('category_id', $product->category_id)->where('season_id', $product->season_id)->orderByDesc('id')->first();
		if ($lastProduct) {
			$product->id = $lastProduct->id + 1;
		} else {
			$product->id = $product->category_id.$product->season_id.'001';
		}
		$product->save();
		return redirect(route('images.edit', ['product' => $product]));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Product  $product
	 * @return \Illuminate\Http\Response
	 */
	public function show(Product $product)
	{
		$product->load(['images' => function ($query) {
			$query->orderBy('website_id', 'ASC')->orderBy('type_id', 'ASC');
		}]);
		return view('products.show', compact('product'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Product  $product
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Product $product)
	{
		$product->load(['images' => function ($query) {
			$query->orderBy('website_id', 'ASC')->orderBy('type_id', 'ASC');
		}]);
		return view('products.edit', compact('product'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Product  $product
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Product $product)
	{
		$product->update($this->validateProduct());
		return redirect(route('products.show', ['product' => $product]));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Product  $product
	 * @return \Illuminate\Http\Response
	 */
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
		$filters = $this->validateFilters();
		foreach ($filters as $field => $values) {
			$query->whereIn("{$field}_id", $values);
		}
		return $query;
	}
	public function sortOptions()
	{
		return ['default', 'random','price-high-to-low','price-low-to-high','hottest','best-selling','newest','oldest'];
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
					return $item->getMinPrice('retail', 0);
				});
				break;

			case 'price-low-to-high':
				$products = $products->sortBy(function ($item) {
					return $item->getMinPrice('retail', INF);
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
}

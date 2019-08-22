<?php

namespace App\Http\Controllers;

use App\Product;
use App\Retailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RetailerController extends Controller
{
	public function search(Request $request)
	{
		$not_found = false;
		if ($request->input('name')) {
			$retailer = \App\Retailer::where('name', $request->input('name'))->first();
			if ($retailer) {
				return redirect(route('retailer.products.index', ['retailer' => $retailer,]));
			} else {
				$not_found = true;
			}
		}
		return view('retailer.search', compact('not_found'));
	}
  
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, Retailer $retailer)
	{
		$products = Cache::remember(url()->full(), 10, function () use ($retailer) {
			$products = $this->filter($retailer->products());
			$products = $this->sort($products);
			$products->load(['image','brand','retails' => function ($query) use ($retailer) {
				$query->where('retailer_id', $retailer->id);
			}]);
			return $products;
		});
		if ($request->input('sort') === 'random') {
			$products = $products->shuffle();
		}
		$user = auth()->user();
		if ($user) {
			$products->each(function ($product, $index) use ($user) {
				$product->retails = $product->retails->whereIn('retailer_id', $user->following_retailers->pluck('id'));
			});
		} else {
			$products->each(function ($product, $index) use ($user) {
				$product->retails = $product->retails->where('retailer_id', 1);
			});
		}

		$sortOptions = $this->sortOptions();
		$filters = $this->filterOptions();
		$request->flash();
		return view('retailer.products.index', compact('products', 'sortOptions', 'filters', 'user', 'retailer'));
	}
  
	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Product  $product
	 * @return \Illuminate\Http\Response
	 */
	public function show(Retailer $retailer, Product $product)
	{
		$product->load(['images', 'retails' => function ($query) use ($retailer) {
			$query->where('retailer_id', $retailer->id);
		}]);
		return view('retailer.products.show', compact('product', 'retailer'));
	}
	public function follow(Retailer $retailer)
	{
		$user = auth()->user();
		return $user->following_retailers()->syncWithoutDetaching($retailer);
	}
	
  public function unfollow(Retailer $retailer)
	{
		$user = auth()->user();
		return $user->following_retailers()->detach($retailer);
	}
	
  public function following()
	{
		$user = auth()->user();
		$retailers = $user->following_retailers;
		return view('retailer.following', compact('retailers'));
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
		if ($request->input('show_available_only')) {
			$products = $products->filter(function ($item) {
				return $item->retails->isNotEmpty();
			});
		}

		# admin filters
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
			if ($vendors = $request->input('vendor')) {
				$products = $products->filter(function ($item) use ($vendors) {
					return $item->offer->whereIn('vendor_id', $vendors)->isNotEmpty();
				});
			}
		} else {
			$products = $products->filter(function ($item) {
				return $item->images->isNotEmpty();
			});
		}

		# vendor filters
		if ($user && ($vendor = $user->vendor) && $request->input('show_my_stock_only')) {
			$products = $products->filter(function ($item) use ($vendor) {
				return $item->offer->where('vendor_id', $vendor->id)->isNotEmpty();
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

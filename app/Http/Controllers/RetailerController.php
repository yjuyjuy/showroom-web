<?php

namespace App\Http\Controllers;

use App\Product;
use App\Retailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class RetailerController extends Controller
{
	public function index(Request $request, Retailer $retailer)
	{
		$user = auth()->user();
		$products = $this->filter($retailer);
		$products = $this->sort($products);
		$sortOptions = $this->sortOptions();
		$filters = $this->filterOptions();
		$request->flash();
		return view('retailers.products.index', compact('products', 'sortOptions', 'filters', 'user', 'retailer'));
	}

	public function show(Retailer $retailer, Product $product)
	{
		$product->load(['images', 'retails' => function ($query) use ($retailer) {
			$query->where('retailer_id', $retailer->id);
		}]);
		return view('retailers.products.show', compact('product', 'retailer'));
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

  public function following(Request $request)
	{
		$not_found = false;
		if ($request->input('name')) {
			$name = $request->validate([
				'name' => ['sometimes', 'string', 'max:255'],
			])['name'];
			$retailer = \App\Retailer::where('name', $name)->first();
			if ($retailer) {
				return redirect(route('retailer.products.index', ['retailer' => $retailer,]));
			} else {
				$not_found = true;
			}
			$request->flash();
		}
		$user = auth()->user();
		$retailers = $user->following_retailers;
		return view('retailers.following', compact('retailers', 'not_found'));
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

	public function filter($retailer)
	{
		$query = $retailer->products();
		$request = request();
		$filters = $this->validateFilters();
		foreach ($filters as $field => $values) {
			$query->whereIn("{$field}_id", $values);
		}
		$products = $query->get();
		$user = auth()->user();
		$products->load(['retails' => function ($query) use ($retailer) {
			$query->where('retailer_id', $retailer->id);
		}, 'image', 'brand']);
		$products = $products->filter(function ($item) {
			return $item->retails->isNotEmpty() && $item->image;
		});
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
}

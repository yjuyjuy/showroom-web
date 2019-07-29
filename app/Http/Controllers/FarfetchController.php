<?php

namespace App\Http\Controllers;

use App\FarfetchProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class FarfetchController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$products = Cache::rememberForever(url()->full(), function () use ($request) {
			$products = $this->filter(FarfetchProduct::with(['designer','categories']));
			$products = $this->sort($products);
			$products->load(['images']);
			return $products;
		});
		if ($request->input('sort') === 'random') {
			$products = $products->shuffle();
		}
		$sortOptions = $this->sortOptions();
		$filters = [
			"category" => \App\FarfetchCategory::all(),
			"designer" => \App\FarfetchDesigner::all()
		];
		$request->flash();
		return view('farfetch.index', compact('products', 'sortOptions', 'filters'));
	}

	public function validateFilters()
	{
		return request()->validate([
			'designer.*' => 'sometimes|exists:farfetch.designers,id',
			'category.*' => 'sometimes|exists:farfetch.categories,id',
		]);
	}

	public function filter($query)
	{
		$request = request();
		$filters = $this->validateFilters();
		if($filters['designer'] ?? false){
				$query->whereIn("designer_id", $filters['designer']);
		}
		if($filters['category'] ?? false){
			$query->where(function ($query) use ($filters){
				foreach($filters['category'] as $category_id){
          $query->orWhereJsonContains('category_ids', $category_id);
				}
      });
		}
		$products = $query->get();
		return $products;
	}

	public function sortOptions()
	{
		return [
			'default', 'random', 'category-asc', 'category-desc'
			// 'price-high-to-low','price-low-to-high','hottest','best-selling','newest','oldest'
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
				$products = $products->sortByDesc(function ($item) {
					return $item->id;
				});
				break;

			case 'random':
				$products = $products->shuffle();
				break;

			case 'category-asc':
				$products = $products->sortBy(function ($item) {
					return $item->categories->max();
				});
				break;

			case 'category-desc':
				$products = $products->sortByDesc(function ($item) {
					return $item->categories->max();
				});
				break;


			default:
				$products = $products->sortBy(function ($item) {
					return $item->id;
				});
				break;
		}
		return $products;
	}
	/**
	 * Display the specified resource.
	 *
	 * @param  \App\FarfetchProduct  $farfetchProduct
	 * @return \Illuminate\Http\Response
	 */
	public function show(FarfetchProduct $product)
	{
		$product->loadMissing(['categories','designer','images']);
		return view('farfetch.show', compact('product'));
	}
}

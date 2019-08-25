<?php

namespace App\Http\Controllers;

use App\FarfetchProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class FarfetchController extends Controller
{
	public function index(Request $request, $gender='men', $designer='off-white')
	{
		// Cache::forget(url()->full());
		$products = Cache::rememberForever(url()->full(), function () use ($request, $gender, $designer) {
			$query = FarfetchProduct::query();
			$products = $this->filter($query, $gender, $designer)->with(['category'])->get();
			$this->sort($products);
			$products->loadMissing(['designer','image']);
			return $products;
		});
		$sortOptions = $this->sortOptions();
		$filters = [
			"category" => \App\FarfetchCategory::where('cat', 1)->where('gender',$gender)->get(),
			"designer" => \App\FarfetchDesigner::all(),
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

	public function filter($query, $gender, $designer)
	{
		$designer_id = \App\FarfetchDesigner::where('urlToken', $designer)->first()->id;
		$query->where('gender',$gender)->where('designer_id', $designer_id);
		$filters = $this->validateFilters();
		if(empty($filters['category'])){
			$filters['category'] = \App\FarfetchCategory::where('cat',1)->where('gender','men')->get('id')->toArray();
		}
		foreach($filters as $key => $values){
			$query->whereIn("{$key}_id",$values);
		}
		return $query;
	}

	public function sortOptions()
	{
		return [
			'default', 'random', 'category-asc', 'category-desc'
			// 'price-high-to-low','price-low-to-high','hottest','best-selling','newest','oldest'
		];
	}

	public function sort(&$products)
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
					return $item->category_id;
				});
				break;

			case 'category-desc':
				$products = $products->sortByDesc(function ($item) {
					return $item->category_id;
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
	public function show(FarfetchProduct $product)
	{
		$product->loadMissing(['categories','designer','images']);
		return view('farfetch.show', compact('product'));
	}
}

<?php

namespace App\Http\Controllers;

use App\Product;
use App\FarfetchProduct;
use App\FarfetchDesigner;
use App\FarfetchCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;

class FarfetchController extends Controller
{
	public $retailer_id = 1467053076;

	public function index(Request $request)
	{
		$filters = [
			'designer' => $this->getDesigners(),
			'category' => $this->getCategories(),
		];
		$sortOptions = $this->getSortOptions();
		$data = $request->validate([
			'designer.*' => ['sometimes', Rule::in($filters['designer']->pluck('id'))],
			'category.*' => ['sometimes', Rule::in($filters['category']->pluck('id'))],
			'sort' => ['sometimes', Rule::in($sortOptions)],
		]);
		$query = FarfetchProduct::query();
		if (!empty($data['designer'])) {
			$query->whereIn('designer_id', $data['designer']);
		}
		if (!empty($data['category'])) {
			$query->orWhere('category_id', $data['category']);
		}
		if (!empty($data['sort'])) {
			if ($data['sort'] == 'price-low-to-high') {
				$query->where('price', '>', 0)->orderBy('price')->orderBy('id', 'desc');
			} elseif ($data['sort'] == 'price-high-to-low') {
				$query->where('price', '>', 0)->orderBy('price', 'desc')->orderBy('id', 'desc');
			} else {
				$query->orderBy('id', 'desc');
			}
		} else {
			$query->orderBy('id', 'desc');
		}

		$total_pages = ceil($query->count() / 48.0);
		$page = min(max($request->query('page', 1), 1), $total_pages);
		$products = $query->forPage($page, 48)->with('designer', 'category')->get();

		$request->flash();
		return view('farfetch.index', compact('products', 'sortOptions', 'filters', 'page', 'total_pages'));
	}

	public function show(FarfetchProduct $product)
	{
		$product->loadMissing(['category','designer','images']);
		return view('farfetch.show', compact('product'));
	}

	public function getDesigners()
	{
		return Cache::remember('farfetch-designers', 60 * 60, function () {
			return FarfetchDesigner::has('products')->orderBy('description')->get();
		});
	}

	public function getCategories()
	{
		return Cache::remember('farfetch-categories', 60 * 60, function () {
			return FarfetchCategory::has('products')->orderBy('description')->get();
		});
	}

	public function getSortOptions()
	{
		return ['default', 'price-low-to-high', 'price-high-to-low'];
	}

	public function export(FarfetchProduct $farfetch_product)
	{
		$product = Product::create([
			'brand_id' => $farfetch_product->designer->mapped_id,
			'designer_style_id' => $farfetch_product->designer_style_id,
			'name_cn' => $farfetch_product->short_description,
			'name' => $farfetch_product->short_description,
			'category_id' => $farfetch_product->category->mapped_id,
			'id' => \App\Product::generate_id(),
		]);
		$retail = new \App\RetailPrice();
		$retail->retailer_id = $this->retailer_id;
		$retail->product_id = $product->id;
		$image_controller = new ImageController();
		if (!empty($farfetch_product->size_price)) {
			$retail->merge($farfetch_product->size_price);
			$retail->link = $farfetch_product->url;
		}
		$image_controller->import($farfetch_product->images, $product);
		$farfetch_product->product_id = $product->id;
		$farfetch_product->save();
		foreach(\App\FarfetchProduct::where('designer_id', $farfetch_product->designer_id)->where('designer_style_id', $farfetch_product->designer_style_id)->where('colors', $farfetch_product->colors)->whereNull('product_id')->get() as $p) {
			if (!empty($p->size_price)) {
				$retail->merge($p->size_price);
				$retail->link = $p->url;
			}
			$image_controller->import($p->images, $product);
			$p->product_id = $product->id;
			$p->save();
		}
		if (!empty($retail->prices)) {
			$retail->save();
		} else {
			$retail->delete();
		}
		return redirect(route('products.show', ['product' => $product,]));
	}

	public function merge(FarfetchProduct $farfetch_product, Product $product)
	{
		foreach([
			'brand_id' => $farfetch_product->designer->mapped_id,
			'designer_style_id' => $farfetch_product->designer_style_id,
			'name_cn' => $farfetch_product->short_description,
			'name' => $farfetch_product->short_description,
			'category_id' => $farfetch_product->category->mapped_id,
		] as $key => $value) {
			if (empty($product[$key])) {
				$product[$key] = $value;
			}
		}
		$farfetch_product->product_id = $product->id;
		$farfetch_product->save();
		(new ImageController())->import($farfetch_product->images, $product);
		$retail = \App\RetailPrice::firstOrNew([
			'retailer_id' => $this->retailer_id,
			'product_id' => $product->id,
		]);
		$retail->prices = [];
		foreach($product->farfetch_products as $p) {
			$retail->merge($p->size_price);
		}
		if(!empty($retail->prices)) {
			$retail->save();
		} else {
			$retail->delete();
		}
		return redirect(route('products.show', ['product' => $product,]));
	}

	public function unlink(FarfetchProduct $farfetch_product)
	{
		$product = $farfetch_product->product;
		$farfetch_product->product_id = NULL;
		$farfetch_product->save();

		$retail = \App\RetailPrice::firstOrNew([
			'retailer_id' => $this->retailer_id,
			'product_id' => $product->id,
		]);
		$retail->prices = [];
		foreach($product->farfetch_products as $p) {
			$retail->merge($p->size_price);
		}
		if(!empty($retail->prices)) {
			$retail->save();
		} else {
			$retail->delete();
		}
		return redirect(route('farfetch.show', ['product' => $farfetch_product]));
	}
}

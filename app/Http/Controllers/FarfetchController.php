<?php

namespace App\Http\Controllers;

use App\Product;
use App\FarfetchProduct;
use App\FarfetchDesigner;
use App\FarfetchCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class FarfetchController extends Controller
{
	public function index(Request $request, $token=null)
	{
		$categories = $this->getCategories();
		$designers = $this->getDesigners();
		$data = $request->validate([
			'designer.*' => ['sometimes', Rule::in($designers->pluck('id'))],
			'category.*' => ['sometimes', Rule::in($categories->pluck('id'))],
			'sort' => ['sometimes', Rule::in($this->getSortOptions())],
		]);
		$filters = [];
		$query = FarfetchProduct::query();
		$designer = $designers->where('url_token', $token)->first();
		$category = $categories->where('url_token', $token)->first();
		if ($designer) {
			$query->where('designer_id', $designer->id);
		} else {
			$filters['designer'] = $designers;
			if (!empty($data['designer'])) {
				$query->where(function ($query) use ($data) {
					foreach ($data['designer'] as $designer_id) {
						$query->orWhere('designer_id', $designer_id);
					}
				});
			}
		}
		if ($category) {
			$query->where('category_id', $category->id);
		} else {
			$filters['category'] = $categories;
			if (!empty($data['category'])) {
				$query->where(function ($query) use ($data) {
					foreach ($data['category'] as $category_id) {
						$query->orWhere('category_id', $category_id);
					}
				});
			}
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
		$query->has('images');
		$total_pages = ceil($query->count() / 48.0);
		$page = min(max($request->query('page', 1), 1), $total_pages);
		$products = $query->skip(($page - 1) * 48)->take(48)->with('designer', 'category')->get();

		$sortOptions = $this->getSortOptions();
		$request->flash();
		return view('farfetch.index', compact('products', 'designer', 'designers', 'category', 'categories', 'sortOptions', 'filters', 'page', 'total_pages'));
	}

	public function show(FarfetchProduct $product)
	{
		$product->loadMissing(['category','designer','images']);
		return view('farfetch.show', compact('product'));
	}

	public function designers()
	{
		$designers = FarfetchDesigner::all();
		return view('farfetch.designers', compact('designers'));
	}

	public function getDesigners()
	{
		return Cache::remember('farfetch-designers', 60 * 60, function () {
			return FarfetchDesigner::has('products')->orderBy('description')->get();
		});
	}

	public function categories()
	{
		$categories = $this->getCategories();
		return view('farfetch.categories', compact('categories'));
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
		$retailer_id = 1467053076;
		$product = Product::create([
			'brand_id' => $farfetch_product->designer->brand_id,
			'designer_style_id' => $farfetch_product->designer_style_id,
			'name_cn' => $farfetch_product->short_description,
			'name' => $farfetch_product->short_description,
			'category_id' => $farfetch_product->category->mapped_id,
			'id' => \App\Product::generate_id(),
		]);
		$retail = new \App\RetailPrice();
		$retail->retailer_id = $retailer_id;
		$retail->product_id = $product->id;
		$image_controller = new ImageController();
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
		$retailer_id = 1467053076;
		foreach([
			'brand_id' => $farfetch_product->designer->brand_id,
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
			'retailer_id' => $retailer_id,
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
		$retailer_id = 1467053076;
		$product = $farfetch_product->product;
		$farfetch_product->product_id = NULL;
		$farfetch_product->save();
		$retail = \App\RetailPrice::firstOrNew([
			'retailer_id' => $retailer_id,
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

<?php

namespace App\Http\Controllers;

use App\Product;
use App\SsenseBrand;
use App\SsenseImage;
use App\SsenseProduct;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;

class SsenseController extends Controller
{
	public function index(Request $request)
	{
		$filters = [
			'brand' => $this->getBrands(),
			'category' => $this->getCategories(),
		];
		$sortOptions = $this->getSortOptions();
		$data = $request->validate([
			'brand.*' => ['sometimes', Rule::in(array_keys($filters['brand']))],
			'category.*' => ['sometimes', Rule::in(array_keys($filters['category']))],
			'sort' => ['sometimes', Rule::in($sortOptions)],
		]);

		$query = SsenseProduct::query();
		if (!empty($data['brand'])) {
			$query->whereIn('brand_name', array_map(function ($brand) use ($filters) {
				return $filters['brand'][$brand];
			}, $data['brand']));
		}
		if (!empty($data['category'])) {
			$query->whereIn('category', $data['category']);
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
		$products = $query->forPage($page, 48)->with('brand')->get();

		$request->flash();
		return view('ssense.index', compact('products', 'sortOptions', 'filters', 'page', 'total_pages'));
	}

	public function show(SsenseProduct $product)
	{
		$product->loadMissing(['brand','images']);
		return view('ssense.show', compact('product'));
	}

	public function getBrands()
	{
		return Cache::remember('ssense-brands', 60 * 60, function () {
			return SsenseBrand::has('products')->orderBy('name')->pluck('name', 'url_token')->toArray();
		});
	}

	public function getCategories()
	{
		return Cache::remember('ssense-categories', 60 * 60, function () {
			return SsenseProduct::orderBy('category')->pluck('category', 'category')->toArray();
		});
	}

	public function getSortOptions()
	{
		return ['default', 'price-low-to-high', 'price-high-to-low'];
	}

	public function export(SsenseProduct $ssense_product)
	{
		$product = Product::create([
			'brand_id' => $ssense_product->brand->mapped_id,
			'name_cn' => $ssense_product->name,
			'name' => $ssense_product->name,
			'id' => \App\Product::generate_id(),
		]);
		$ssense_product->product_id = $product->id;
		$ssense_product->save();
		(new ImageController())->import($ssense_product->images, $product);
		return redirect(route('products.show', ['product' => $product,]));
	}

	public function merge(SsenseProduct $ssense_product, Product $product)
	{
		foreach ([
			'brand_id' => $ssense_product->brand->mapped_id,
			'name_cn' => $ssense_product->name,
			'name' => $ssense_product->name,
		] as $key => $value) {
			if (empty($product[$key])) {
				$product[$key] = $value;
			}
		}
		$ssense_product->product_id = $product->id;
		$ssense_product->save();
		(new ImageController())->import($ssense_product->images, $product);
		return redirect(route('products.show', ['product' => $product,]));
	}

	public function unlink(SsenseProduct $ssense_product)
	{
		$product = $ssense_product->product;
		$ssense_product->product_id = null;
		$ssense_product->save();
		return redirect(route('ssense.show', ['product' => $ssense_product]));
	}
}

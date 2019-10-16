<?php

namespace App\Http\Controllers;

use App\Product;
use App\EndProduct;
use App\EndImage;
use App\EndBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class EndController extends Controller
{
	public function index(Request $request, $token=null)
	{
		$departments = $this->getDepartments();
		$brands = $this->getBrands();
		$sortOptions = $this->getSortOptions();
		$data = $request->validate([
			'brand.*' => ['sometimes', Rule::in(array_keys($brands))],
			'department.*' => ['sometimes', Rule::in(array_keys($departments))],
			'sort' => ['sometimes', Rule::in($sortOptions)],
		]);
		$filters = [];
		$query = EndProduct::query();
		if (array_key_exists($token, $brands)) {
			$brand = $token;
			$query->where('brand_name', $brands[$token]);
		} else {
			$brand = null;
			$filters['brand'] = $brands;
			if (!empty($data['brand'])) {
				$query->where(function ($query) use ($data, $brands) {
					foreach ($data['brand'] as $token) {
						$query->orWhere('brand_name', $brands[$token]);
					}
				});
			}
		}
		if (array_key_exists($token, $departments)) {
			$department = $token;
			$query->where('department', $departments[$token]);
		} else {
			$department = null;
			$filters['department'] = $departments;
			if (!empty($data['department'])) {
				$query->where(function ($query) use ($data, $departments) {
					foreach ($data['department'] as $token) {
						$query->orWhere('department', $departments[$token]);
					}
				});
			}
		}
		if (!empty($data['sort'])) {
			if ($data['sort'] == 'price-low-to-high') {
				$query->where('price', '>', 0)->orderBy('price');
			} elseif ($data['sort'] == 'price-high-to-low') {
				$query->where('price', '>', 0)->orderBy('price', 'desc');
			} else {
				$query->orderBy('id');
			}
		}

		$total_pages = ceil($query->count() / 48.0);
		$page = min(max($request->query('page', 1), 1), $total_pages);
		$products = $query->skip(($page - 1) * 48)->take(48)->get();

		$sortOptions = $this->getSortOptions();
		$request->flash();
		return view('end.index', compact('products', 'brand', 'department', 'brands', 'departments', 'sortOptions', 'filters', 'page', 'total_pages'));
	}

	public function show(EndProduct $product)
	{
		$product->loadMissing(['images']);
		return view('end.show', compact('product'));
	}

	public function brands()
	{
		$brands = $this->getBrands();
		return view('end.brands', compact('brands'));
	}

	public function getBrands()
	{
		return Cache::remember('end-brands', 60 * 60, function () {
			$brands = [];
			foreach (EndBrand::has('products')->orderBy('name')->get() as $brand) {
				$brands[$brand->url_token] = $brand->name;
			}
			return $brands;
		});
	}

	public function departments()
	{
		$departments = $this->getDepartments();
		return view('end.departments', compact('departments'));
	}

	public function getDepartments()
	{
		return Cache::remember('end-departments', 60 * 60, function () {
			$departments = [];
			foreach (EndProduct::pluck('department')->unique() as $department) {
				$token = Str::slug($department);
				$departments[$token] = $department;
			}
			return $departments;
		});
	}

	public function getSortOptions()
	{
		return ['default', 'price-low-to-high', 'price-high-to-low'];
	}

	public function export(EndProduct $end_product, Product $product=null)
	{
		$retailer_id = 3548857028;
		$website_id = 6;
		if ($product) {
			foreach ([
				'brand_id' => $end_product->brand->id,
				'designer_style_id' => $end_product->sku,
				'name_cn' => $end_product->name,
				'name' => $end_product->name,
			] as $key => $value) {
				if (empty($product[$key])) {
					$product[$key] = $value;
				}
			}
			$product->save();
		} else {
			$product = Product::create([
				'brand_id' => $end_product->brand->id,
				'designer_style_id' => $end_product->sku,
				'name_cn' => $end_product->name,
				'name' => $end_product->name,
				'id' => \App\Product::generate_id(),
			]);
		}
		$retail = \App\RetailPrice::firstOrNew([
			'retailer_id' => $retailer_id,
			'product_id' => $product->id,
		]);
		$retail->merge($end_product->size_price);
		if (!empty($retail->prices)) {
			$retail->save();
		} else {
			$retail->delete();
		}
		if ($end_product->images->isNotEmpty()) {
			(new ImageController())->import($end_product->images, $product, $website_id);
		}
		return redirect(route('products.show', ['product' => $product,]));
	}
}

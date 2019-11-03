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
			$query->where('department_name', $departments[$token]);
		} else {
			$department = null;
			$filters['department'] = $departments;
			if (!empty($data['department'])) {
				$query->where(function ($query) use ($data, $departments) {
					foreach ($data['department'] as $token) {
						$query->orWhere('department_name', $departments[$token]);
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
				$brands[Str::slug($brand->name)] = $brand->name;
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
			foreach (EndProduct::pluck('department_name')->unique() as $department) {
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

	public function export(EndProduct $end_product)
	{
		$retailer_id = 3548857028;
		$product = Product::create([
			'brand_id' => $end_product->brand->mapped_id,
			'designer_style_id' => $end_product->sku,
			'name_cn' => $end_product->name,
			'name' => $end_product->name,
			'category_id' => $end_product->department->mapped_id,
			'id' => \App\Product::generate_id(),
		]);
		$end_product->product_id = $product->id;
		$end_product->save();

		$retail = new \App\RetailPrice();
		$retail->retailer_id = $retailer_id;
		$retail->product_id = $product->id;
		$image_controller = (new ImageController());
		foreach(\App\EndProduct::where('sku', $end_product->sku)->where('brand_name', $end_product->brand_name)->where('color', $end_product->color)->get() as $p){
			$retail->merge($p->size_price);
			$image_controller->import($p->images, $product);
			$retail->link = $p->url;
		}
		if (!empty($retail->prices)) {
			$retail->save();
		} else {
			$retail->delete();
		}
		return redirect(route('products.show', ['product' => $product,]));
	}

	public function merge(EndProduct $end_product, Product $product)
	{
		$retailer_id = 3548857028;
		foreach ([
			'brand_id' => $end_product->brand->mapped_id,
			'designer_style_id' => $end_product->sku,
			'name_cn' => $end_product->name,
			'name' => $end_product->name,
			'category_id' => $end_product->department->mapped_id,
		] as $key => $value) {
			if (empty($product[$key])) {
				$product[$key] = $value;
			}
		}
		$product->save();
		$end_product->product_id = $product->id;
		$end_product->save();

		$retail = \App\RetailPrice::firstOrNew([
			'retailer_id' => $retailer_id,
			'product_id' => $product->id,
		]);
		(new ImageController())->import($end_product->images, $product);
		foreach(\App\EndProduct::where('product_id', $product->id)->get() as $p){
			$retail->merge($p->size_price);
		}
		if (!empty($retail->prices)) {
			$retail->save();
		} else {
			$retail->delete();
		}
		return redirect(route('products.show', ['product' => $product,]));
	}

	public function unlink(EndProduct $end_product)
	{
		$end_product->product_id = NULL;
		$end_product->save();
		return redirect(route('end.show', ['product' => $end_product]));
	}
}

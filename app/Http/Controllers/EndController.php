<?php

namespace App\Http\Controllers;

use App\EndProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

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
		if (in_array($token, $brands)) {
			$brand = $token;
			$query->where('brand', $brand);
		} else {
			$brand = NULL;
			$filters['brand'] = $brands;
			if (!empty($data['brand'])) {
				$query->where(function ($query) use ($data, $brands) {
					foreach ($data['brand'] as $token) {
						$query->orWhere('brand', $brands[$token]);
					}
				});
			}
		}
		if (in_array($token, $departments)) {
			$department = $token;
			$query->where('department', $department);
		} else {
			$department = NULL;
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
		return Cache::remember('end-brands', 60 * 60, function() {
			$brands = [];
			foreach(EndProduct::pluck('brand')->unique() as $brand){
				$token = preg_replace('/[^a-z]+/','-',strtolower($brand));
				$brands[$token] = $brand;
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
		return Cache::remember('end-departments', 60 * 60, function() {
			$departments = [];
			foreach(EndProduct::pluck('department')->unique() as $department){
				$token = preg_replace('/[^a-z]+/','-', strtolower($department));
				$departments[$token] = $department;
			}
			return $departments;
		});
	}

	public function getSortOptions()
	{
		return ['default', 'price-low-to-high', 'price-high-to-low'];
	}
}

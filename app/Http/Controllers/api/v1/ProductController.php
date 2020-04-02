<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
	public function index(Request $request) {
		return Cache::remember(request()->fullUrl(), 1 * 60, function() use ($request) {
			if ($request->query('vendor')) {
				$query = Vendor::findOrFail($request->query('vendor'))->products();
			} else {
				$query = Product::query();
			}
			$query->orderBy('updated_at', 'desc');
			$total_pages = ceil($query->count() / 24.0);
			$page = min(max(request()->query('page', 1), 1), $total_pages);
			$products = $query->forPage($page, 24)->get();
			$products->load(['brand', 'images', 'season', 'offers', 'offers.vendor']);
			return [
				'page' => $page,
				'total_pages' => $total_pages,
				'user' => auth()->user(),
				'products' => $products->values(),
			];
		});
	}

	public function show(Product $product) {
		return $product->load([
			'brand', 'season', 'color', 'category', 'prices', 'prices.vendor', 'offers', 'offers.vendor',
			'images' => function($query) {
				$query->orderBy('order', 'asc');
			},
		]);
	}
}

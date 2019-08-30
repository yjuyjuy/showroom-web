<?php

namespace App\Http\Controllers;

use App\VendorPrice;
use App\Vendor;
use App\Product;
use Illuminate\Http\Request;

class PriceController extends Controller
{
	public function index()
	{
		$user = auth()->user();
		if ($user->isSuperAdmin() && ($vendor = request()->input('vendor'))) {
			$vendor = Vendor::find($vendor);
			request()->flash();
		} else {
			$vendor = $user->vendor;
		}
		$products = $vendor->products;
		$products->loadMissing([
			'brand',
			'prices' => function ($query) use ($vendor) {
				$query->where('vendor_id', $vendor->id);
			},
			'images' => function ($query) {
				$query->orderBy('website_id', 'asc')->orderBy('order', 'asc');
			}]);
		return view('prices.index', compact('vendor','products'));
	}

	public function create(Product $product)
	{
		if (auth()->user()->isSuperAdmin() && ($vendor = request()->input('vendor'))) {
			$vendor = \App\Vendor::where('name', $vendor)->first();
		} else {
			$vendor = auth()->user()->vendor;
		}
		return view('prices.create', compact('product', 'vendor'));
	}

	public function store(Request $request, Product $product)
	{
		$this->authorize('create', VendorPrice::class);
		if (auth()->user()->isSuperAdmin()) {
			$vendor = \App\Vendor::find($request->input('vendor'));
		} else {
			$vendor = auth()->user()->vendor;
		}
		$data = json_decode($this->validateRequest()['data']);
		if (!empty($data)) {
			$price = $product->prices()->firstOrNew(['vendor_id' => $vendor->id]);
			$price->data = $data;
			$price->vendor_id = $vendor->id;
			$price->save();
		}
		return ['redirect' => route('products.show', ['product' => $product])];
	}

	public function edit(VendorPrice $price)
	{
		$product = $price->product;
		return view('prices.edit', compact('price', 'product'));
	}

	public function update(Request $request, VendorPrice $price)
	{
		$this->authorize('update', $price);
		$data = json_decode($this->validateRequest()['data']);
		if (empty($data)) {
			$price->delete();
		} else {
			$price->data = $data;
			$price->save();
		}
		return ['redirect' => route('products.show', ['product' => $price->product])];
	}

	public function destroy(VendorPrice $price)
	{
		$this->authorize('delete', $price);
		$price->delete();
		return ['redirect' => route('products.show', ['product' => $price->product])];
	}

	public function validateRequest()
	{
		return request()->validate([
			'data' => ['required','json'],
			'data.*.size' => ['required','regex:/^([0-9]+)|([X]*[SML]+)$/'],
			'data.*.cost' => ['required','integer'],
			'data.*.offer' => ['required','integer'],
			'data.*.retail' => ['required','integer'],
		]);
	}
}

<?php

namespace App\Http\Controllers;

use App\VendorPrice;
use App\Vendor;
use App\Product;
use App\Log;
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
			'images', 'brand', 'prices' => function ($query) use ($vendor) {
				$query->where('vendor_id', $vendor->id)->orderBy('created_at', 'desc');
			},]);
		$products = $products->sortBy(function($product) {
			return $product->prices->first()->created_at;
		})->values();
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
			$product->touch();
			Log::create([
				'content' => auth()->user()->username.'新增了'.$price->vendor->name.'的'.$product->displayName().'的价格',
				'url' => route('products.show', ['product' => $product]),
			]);
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
		} elseif ($price->data == $data) {
			$price->touch();
			$price->save();
		} else {
			$price->data = $data;
			$price->save();
			Log::create([
				'content' => auth()->user()->username.'修改了'.$price->vendor->name.'的'.$price->product->displayName().'的价格',
				'url' => route('products.show', ['product' => $price->product]),
			]);
		}
		return ['redirect' => route('products.show', ['product' => $price->product])];
	}

	public function subtract(Request $request, VendorPrice $price, $size)
	{
		$this->authorize('update', $price);
		$data = $price->data;
		foreach($data as $index => &$row) {
			if ($row['size'] == $size) {
				$row['stock'] -= 1;
				if($row['stock'] <= 0) {
					unset($data[$index]);
					if (empty($data)) {
						$price->delete();
						Log::create([
							'content' => auth()->user()->username.'删除了'.$price->vendor->name.'的'.$price->product->displayName().'的价格',
							'url' => route('products.show', ['product' => $price->product]),
						]);
					} else {
						Log::create([
							'content' => auth()->user()->username.'修改了'.$price->vendor->name.'的'.$price->product->displayName().'的价格',
							'url' => route('products.show', ['product' => $price->product]),
						]);
					}
				}
				break;
			}
		}
		$price->data = $data;
		$price->save();
		return ['success'];
	}

	public function add(Request $request, VendorPrice $price, $size)
	{
		$this->authorize('update', $price);
		$data = $price->data;
		foreach($data as &$row) {
			if ($row['size'] == $size) {
				$row['stock'] += 1;
				break;
			}
		}
		$price->data = $data;
		$price->save();
		return ['success'];
	}

	public function destroy(VendorPrice $price)
	{
		$this->authorize('delete', $price);
		$price->delete();
		Log::create([
			'content' => auth()->user()->username.'删除了'.$price->vendor->name.'的'.$price->product->displayName().'的价格',
			'url' => route('products.show', ['product' => $price->product]),
		]);
		return ['redirect' => route('products.show', ['product' => $price->product])];
	}

	public function validateRequest()
	{
		return request()->validate([
			'data' => ['required','json'],
			'data.*.size' => ['required','regex:/^([0-9.XSML]+)$/'],
			'data.*.offer' => ['required','integer'],
			'data.*.retail' => ['required','integer'],
			'data.*.stock' => ['required','integer'],
		]);
	}
}

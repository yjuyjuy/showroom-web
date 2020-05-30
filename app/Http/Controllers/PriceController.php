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
		if ($user->is_admin && ($vendor = request()->input('vendor'))) {
			$vendor = Vendor::find($vendor);
			request()->flash();
		} else {
			$vendor = $user->vendor;
		}
		$brand = $request->validate([
			'brand' => 'sometimes|exists:brands,id',
		])['brand'] ?? null;
		if ($brand) {
			$products = $vendor->products()->where('brand_id', $brand)->get();
		} else {
			$products = $vendor->products;
		}
		$products->loadMissing([
			'images', 'brand', 'prices' => function ($query) use ($vendor) {
				$query->where('vendor_id', $vendor->id)->orderBy('created_at', 'desc');
			},]);
		$products = $products->sortBy(function ($product) {
			return $product->prices->first()->created_at;
		})->values();
		return view('prices.index', compact('vendor', 'products'));
	}

	public function create(Product $product)
	{
		if (auth()->user()->is_admin && ($vendor = request()->input('vendor'))) {
			$vendor = \App\Vendor::where('name', $vendor)->first();
		} else {
			$vendor = auth()->user()->vendor;
		}
		return view('prices.create', compact('product', 'vendor'));
	}

	public function store(Product $product)
	{
		if (auth()->user()->is_admin) {
			$vendor = \App\Vendor::find(request()->input('vendor'));
		} else {
			$vendor = auth()->user()->vendor;
		}
		$data = json_decode($this->validateRequest()['data'], true);
		if (!empty($data)) {
			$price = $product->prices()->firstOrNew(['vendor_id' => $vendor->id]);
			$price->data = $data;
			$price->updated_at = NOW();
			$price->save();
			if ($product->updated_at < NOW()->subday(1)) {
				$product->touch();
			}
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

	public function update(VendorPrice $price)
	{
		$this->authorize('update', $price);
		$data = json_decode($this->validateRequest()['data'], true);
		if (empty($data)) {
			$price->delete();
		} elseif ($price->data == $data) {
			if ($price->updated_at < NOW()->subday(1)) {
				$price->touch();
			}
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

	public function subtract(VendorPrice $price, $size)
	{
		$this->authorize('update', $price);
		$data = $price->data;
		$should_log = false;
		foreach ($data as $index => &$row) {
			if ($row['size'] == $size) {
				$row['stock'] -= 1;
				if ($row['stock'] <= 0) {
					unset($data[$index]);
					$should_log = true;
				}
				break;
			}
		}
		if ($should_log) {
			if (empty($data)) {
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
		if (empty($data)) {
			$price->delete();
		} else {
			$price->data = $data;
			$price->save();
		}
		return ['success'];
	}

	public function add(VendorPrice $price, $size)
	{
		$this->authorize('update', $price);
		$data = $price->data;
		foreach ($data as &$row) {
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
			'data.*.size' => ['required','string'],
			'data.*.offer' => ['required','integer'],
			'data.*.retail' => ['required','integer'],
			'data.*.stock' => ['required','integer'],
		]);
	}
}

<?php

namespace App\Http\Controllers;

use App\Price;
use App\Vendor;
use App\Product;
use Illuminate\Http\Request;

class PricesController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$user = auth()->user();
		if ($user->isSuperAdmin() && ($vendor = request()->input('vendor'))) {
			$vendor = Vendor::find($vendor);
			request()->flash();
		} else {
			$vendor = $user->vendor;
		}
		$vendor->load(['products','products.prices'=>function ($query) use ($vendor) {
			$query->where('vendor_id', $vendor->id);
		},'products.images' => function ($query) {
			$query->orderBy('website_id', 'asc')->orderBy('type_id', 'asc');
		}]);
		return view('prices.index', compact('vendor'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Product $product)
	{
		if (auth()->user()->isSuperAdmin() && ($vendor = request()->input('vendor'))) {
			$vendor = \App\Vendor::find($vendor);
		} else {
			$vendor = auth()->user()->vendor;
		}
		return view('prices.create', compact('product', 'vendor'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, Product $product)
	{
		if (auth()->user()->isSuperAdmin()) {
			$vendor = \App\Vendor::find($request->input('vendor'));
		} else {
			$vendor = auth()->user()->vendor;
		}
		$data = json_decode($this->validateRequest()['data']);
		$price = $product->prices()->firstOrNew(['vendor_id' => '']);
		$price->data = $data;
		$price->vendor_id = $vendor->id;
		$price->save();
		return redirect(route('products.show', ['product' => $price->product]));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Price  $price
	 * @return \Illuminate\Http\Response
	 */
	public function show(Price $price)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Price  $price
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Price $price)
	{
		$product = $price->product;
		return view('prices.edit', compact('price', 'product'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Price  $price
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Price $price)
	{
		$data = json_decode($this->validateRequest()['data']);
		$price->data = $data;
		$price->save();
		return redirect(route('products.show', ['product' => $price->product]));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Price  $price
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Price $price)
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
			'data.*.resell' => ['required','integer'],
			'data.*.retail' => ['required','integer'],
		]);
	}
}

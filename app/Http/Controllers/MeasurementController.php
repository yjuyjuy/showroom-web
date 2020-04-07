<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Measurement;

class MeasurementController extends Controller
{
	public function create(Request $request, Product $product)
	{
		return view('measurements.create', compact('product'));
	}

  public function store(Request $request, Product $product)
  {
		$data = json_decode($this->validateData()['data'], true);
		$measurement = Measurement::firstOrNew(['product_id' => $product->id]);
		$measurement->data = $data;
		$measurement->save();
		$product->loadMissing(['images']);
		return ['redirect' => route('products.show', ['product' => $product])];
  }

	public function edit(Request $request, Product $product)
	{
		if (!$product->measurement) {
			return redirect(route('measurements.create', ['product' => $product,]));
		}
		$measurement = $product->measurement;
		$product->loadMissing(['images']);
		return view('measurements.edit', compact('product', 'measurement'));
	}

  public function update(Request $request, Product $product)
  {
		$data = json_decode($this->validateData()['data'], true);
		$product->measurement->data = $data;
		$product->measurement->save();
		return ['redirect' => route('products.show', ['product' => $product])];
  }


	public function validateData()
	{
		return request()->validate([
			'data' => 'required|json',
		]);
	}
}

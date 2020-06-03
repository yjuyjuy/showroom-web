<?php

namespace App\Http\Controllers\api\v3\seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Measurement;
use App\Product;

class MeasurementController extends Controller
{
	public function store(Request $request, Product $product)
	{
		(new \App\Http\Controllers\MeasurementController())->store($request, $product);
		return ['measurement' => $product->measurement->fresh()];
	}

	public function update(Request $request, Product $product)
	{
		(new \App\Http\Controllers\MeasurementController())->update($request, $product);
		return ['measurement' => $product->measurement->fresh()];
	}

	public function destroy(Request $request, Product $product)
	{		
		(new \App\Http\Controllers\MeasurementController())->destroy($request, $product);
		return ['success'];
	}
}

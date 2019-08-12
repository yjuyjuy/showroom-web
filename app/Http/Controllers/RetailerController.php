<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RetailerController extends Controller
{
	public function home()
	{
		$retailer = auth()->user()->vendor->retailer;
		return view('retailer.home', compact('retailer'));
	}

	public function link(RetailPrice $retail, Product $product)
	{
		$current_retail = RetailPrice::where('product_id', $product->id)->where('retailer_id', $retail->retailer_id)->first();
		if ($current_retail) {
			// merge size-price
			$retail->delete();
		// update crawler_taobao database
		} else {
			$retail->product_id = $product->id;
			$retail->save();
		}
	}
	public function ignore(RetailPrice $retail)
	{
		$retail->delete();
		// update crawler_taobao database
	}
}

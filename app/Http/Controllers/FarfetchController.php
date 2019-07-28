<?php

namespace App\Http\Controllers;

use App\FarfetchProduct;
use Illuminate\Http\Request;

class FarfetchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $products = FarfetchProduct::with(['images','designer','categories',])->get();
			$products = $products->sortBy(function($item){
				return $item->categories->last()->id;
			});
			return view('farfetch.index',compact('products'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FarfetchProduct  $farfetchProduct
     * @return \Illuminate\Http\Response
     */
    public function show(FarfetchProduct $product)
    {
			$product->loadMissing(['categories','designer','images']);
      return view('farfetch.show',compact('product'));
    }
}

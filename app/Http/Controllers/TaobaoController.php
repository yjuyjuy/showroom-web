<?php

namespace App\Http\Controllers;

use App\TaobaoProduct;
use App\TaobaoShop;
use App\TaobaoPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TaobaoController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(TaobaoShop $shop)
	{
		$products = $shop->taobao_products()->get();
		$products = $products->load(['prices'])->filter(function($item) {
			return $item->prices->where('prices')->isNotEmpty();
		});
		return view('taobao.index', compact('shop', 'products'));
	}
	/**
	 * Display the specified resource.
	 *
	 * @param  \App\TaobaoProduct  $product
	 * @return \Illuminate\Http\Response
	 */
	public function show($shop, TaobaoProduct $product)
	{
		if ($shop != $product->shop->name) {
			return redirect(route('taobao.show', ['shop' => $product->shop->name,]));
		}
		$product->load(['prices' => function($query) {
			$query->whereNotNull('prices');
		},'shop']);
		return view('taobao.show', compact('product'));
	}
}

<?php

namespace App\Http\Controllers;

use App\TaobaoProduct;
use App\TaobaoShop;
use App\TaobaoPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TaobaoController extends Controller
{
	public function index(TaobaoShop $shop)
	{
		$products = $shop->taobao_products()->get();
		$products = $products->load(['prices'])->filter(function($item) {
			return $item->prices->where('prices')->isNotEmpty();
		});
		return view('taobao.index', compact('shop', 'products'));
	}
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

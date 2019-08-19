<?php

namespace App\Http\Controllers;

use App\TaobaoProduct;
use App\TaobaoShop;
use App\TaobaoPrice;
use Illuminate\Http\Request;

class TaobaoController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(TaobaoShop $shop)
	{
		$products = $shop->products;
		$products->load(['retails' => function($query) use ($shop) {
			$query->where('retailer_id', $shop->retailer_id);
		}]);
		return view('taobao.index', compact('shop','products'));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function admin(TaobaoShop $shop)
	{
		$shop->load(['prices' => function($query) {
			$query->whereNull('product_id')->where('ignore', false);
		},'prices.taobao_product']);
		return view('taobao.admin', compact('shop'));
	}

	public function link(Request $request)
	{
		$data = $request->validate([
			'product_id' => 'exists:mysql.products,id',
			'price_id' => 'exists:taobao.prices,id',
		]);
		$price = TaobaoPrice::find($data['price_id']);
		$price->product_id = $data['product_id'];
		$price->save();
		if (!$price->shop->is_partner) {
			$retail = \App\Retail::firstOrNew([
				'retailer_id' => $price->shop->retailer_id,
				'product_id' => $price->product_id,
			]);
			$retail->prices = $price->prices;
			$retail->save();
		}
		return ['success' => true,];
	}

	public function ignore(Request $request)
	{
		$data = $request->validate([
			'price_id' => 'exists:taobao.prices,id',
		]);
		$price = TaobaoPrice::find($data['price_id']);
		$price->ignore = true;
		$price->save();
		return ['success' => true,];
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\TaobaoProduct  $product
	 * @return \Illuminate\Http\Response
	 */
	public function show(TaobaoProduct $product)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\TaobaoProduct  $product
	 * @return \Illuminate\Http\Response
	 */
	public function edit(TaobaoProduct $product)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \App\TaobaoShop  $shop
	 * @return \Illuminate\Http\Response
	 */
	public function update(TaobaoShop $shop)
	{
		if($shop->is_partner){
			$diffs = [];
			$taobao_prices = $shop->prices()->whereNotNull('product_id')->whereNotNull('prices')->where('ignore',false)->get();
			$retail_prices = \App\RetailPrice::where('retailer_id', $shop->retailer_id)->get();
			foreach($taobao_prices->load('product') as $price) {
				$retail = $retail_prices->where('product_id',$price->product_id)->first();
				if(!$retail){
					// 需要下架
					$diffs[] = ['retail' => null, 'taobao' => $price, 'product' => $price->product];
				} elseif($retail->prices != $price->prices) {
					// 需要修改
					$diffs[] = ['retail' => $retail, 'taobao' => $price, 'product' => $price->product];
				}
			}
			foreach($retail_prices->load('product') as $retail){
				if(!$taobao_prices->where('product_id',$retail->product_id)->first()){
					// 需要上架
					$diffs[] = ['retail' => $retail, 'taobao' => null, 'product' => $retail->product];
				}
			}
			return view('taobao.partner',compact('shop','diffs'));
		} else {
			\App\RetailPrice::where('retailer_id', $shop->retailer_id)->delete();
			foreach($shop->prices()->whereNotNull('product_id')->where('ignore',false)->get() as $price){
				$retail = RetailPrice::firstOrNew([
					'product_id' => $price->product_id,
					'retailer_id' => $shop->retailer_id,
				]);
				if($retail->prices) {
					$prices = $retail->prices;
					foreach($price->prices as $size => $value){
						if(!array_key_exists($size,$prices) || $value < $prices[$size]){
							$prices[$size] = $value;
						}
					}
					$retail->prices = $prices;
				} else {
					$retail->prices = $price->prices;
				}
				$retail->link = $price->url;
				$retail->save();
			}
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\TaobaoProduct  $product
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(TaobaoProduct $product)
	{
		//
	}
}

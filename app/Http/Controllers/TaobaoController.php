<?php

namespace App\Http\Controllers;

use App\TaobaoProduct;
use App\TaobaoShop;
use App\TaobaoPrice;
use Illuminate\Http\Request;

class TaobaoController extends Controller
{
	public function list()
	{
		// $shops = auth()->user()->following_shops;
		$shops = \App\TaobaoShop::all();
		return view('taobao.shops',compact('shops'));
	}
	
	public function home()
	{
		$shops = \App\TaobaoShop::all();
		return view('taobao.home',compact('shops'));
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function admin()
	{
		$prices = \App\TaobaoPrice::whereNull('product_id')->where('ignore', false)->where('shop_id', 70333625)
			->orderBy('shop_id')->orderBy('taobao_id')->limit(50)
			->with('taobao_product')->get();
		return view('taobao.admin', compact('prices'));
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
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(TaobaoShop $shop)
	{
		$products = $shop->taobao_products()->get();
		$products->load(['prices']);
		return view('taobao.index', compact('shop','products'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\TaobaoProduct  $product
	 * @return \Illuminate\Http\Response
	 */
	public function show($shop, TaobaoProduct $product)
	{
		if($shop != $product->shop->name){
			return redirect(route('taobao.show',['shop' => $product->shop->name,]));
		}
		$product->load(['prices','shop']);
		return view('taobao.show',compact('product'));
	}

	public function diffs(TaobaoShop $shop)
	{
		if(!$shop->is_partner){ abort(418); }
		$diffs = Cache::remember(url()->full(), 60, function() use ($shop) {
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
			return $diffs;
		});
		return view('taobao.diffs',compact('shop','diffs'));
	}
	
	public function reset(TaobaoShop $shop)
	{
		if($shop->is_partner){ 
			$shop->prices->update(['ignore' => false, 'product_id' => null,]);
			return redirect(route('taobao.admin'));
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
}

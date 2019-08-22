<?php

namespace App\Http\Controllers;

use App\Product;
use App\TaobaoShop;
use App\TaobaoProduct;
use App\TaobaoPrice;
use Illuminate\Http\Request;

class TaobaoAdminController extends Controller
{
	public function link(Request $request)
	{
		$data = $request->validate([
			'product_id' => 'exists:mysql.products,id',
			'price_id' => 'exists:taobao.prices,id',
		]);
		$price = TaobaoPrice::find($data['price_id']);
		$this->authorize('update', $price->shop);
		$price->product_id = $data['product_id'];
		$price->ignore = false;
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
		
	public function unlink(Request $request)
	{
		$data = $request->validate([
			'product_id' => 'exists:mysql.products,id',
			'price_id' => 'exists:taobao.prices,id',
		]);
		$price = TaobaoPrice::find($data['price_id']);
		$this->authorize('update', $price->shop);
		if ($price->product_id == $data['product_id']) {
			$price->product_id = null;
			$price->save();
		}
		if (!$price->shop->is_partner) {
			$retails = \App\Retail::where('retailer_id', $price->shop->retailer_id)->where('product_id', $price->product_id)->get();
			if ($retails->isNotEmpty()) {
				foreach ($retails as $retail) {
					$retail->delete();
				}
			}
		}
		return ['success' => true,];
	}

	public function ignore(Request $request)
	{
		$data = $request->validate([
			'price_id' => 'exists:taobao.prices,id',
		]);
		$price = TaobaoPrice::find($data['price_id']);
		$this->authorize('update', $price->shop);
		$price->ignore = true;
		$price->save();
		return ['success' => true,];
	}
	
	public function reset(Request $request)
	{
		$data = $request->validate([
			'price_id' => 'exists:taobao.prices,id',
		]);
		$price = TaobaoPrice::find($data['price_id']);
		$this->authorize('update', $price->shop);
		$price->ignore = false;
		$price->save();
		return ['success' => true,];
	}
	
	public function shops()
	{
		$shops = \App\TaobaoShop::all();
		return view('taobao.shops', compact('shops'));
	}
	
	public function admin(TaobaoShop $shop)
	{
		$this->authorize('update', $shop);
		return view('taobao.admin', compact('shop'));
	}

	public function links(TaobaoShop $shop = null)
	{
		if (!$shop) {
			$prices = \App\TaobaoPrice::whereNotNull('prices')->whereNull('product_id')->where('ignore', false)->get();
		} else {
			$prices = $shop->prices()->whereNotNull('prices')->whereNull('product_id')->where('ignore', false)->get();
		}
		return view('taobao.links', compact('prices'));
	}

	public function linked(TaobaoShop $shop)
	{
		$prices = $shop->prices()->whereNotNull('prices')->whereNotNull('product_id')->where('ignore', false)->get();
		return view('taobao.links', compact('prices'));
	}
	
	public function ignored(TaobaoShop $shop)
	{
		$prices = $shop->prices()->whereNotNull('prices')->where('ignore', true)->get();
		return view('taobao.links', compact('prices'));
	}

	public function diffs(TaobaoShop $shop)
	{
		if ($shop->is_partner) {
			$diffs = [];
			$taobao_prices = $shop->prices()->whereNotNull('product_id')->whereNotNull('prices')->where('ignore', false)->get();
			$retail_prices = \App\RetailPrice::where('retailer_id', $shop->retailer_id)->get();
			foreach ($taobao_prices->load('product', 'product.image') as $price) {
				$retail = $retail_prices->where('product_id', $price->product_id)->first();
				if (!$retail) {
					// 需要下架
					$diffs[] = ['retail' => null, 'taobao' => $price, 'product' => $price->product];
				} elseif ($retail->prices != $price->prices) {
					// 需要修改
					$diffs[] = ['retail' => $retail, 'taobao' => $price, 'product' => $price->product];
				}
			}
			foreach ($retail_prices->load('product') as $retail) {
				if (!$taobao_prices->where('product_id', $retail->product_id)->first()) {
					// 需要上架
					$diffs[] = ['retail' => $retail, 'taobao' => null, 'product' => $retail->product];
				}
			}
			return view('taobao.diffs', compact('shop', 'diffs'));
		} else {
			abort(403);
		}
	}
}

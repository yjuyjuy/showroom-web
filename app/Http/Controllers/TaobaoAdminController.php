<?php

namespace App\Http\Controllers;

use App\Product;
use App\TaobaoShop;
use App\TaobaoProduct;
use App\TaobaoPrice;
use Illuminate\Http\Request;

class TaobaoAdminController extends Controller
{
	public function index()
	{
		$shops = \App\TaobaoShop::all();
		return view('taobao.admin.index', compact('shops'));
	}

	public function admin(TaobaoShop $shop)
	{
		$this->authorize('update', $shop);
		return view('taobao.admin.admin', compact('shop'));
	}

	public function links(TaobaoShop $shop = null)
	{
		if (!$shop) {
			$prices = \App\TaobaoPrice::whereNotNull('prices')->whereNull('product_id')->where('ignore', false)->orderBy('taobao_id')->get();
		} else {
			$prices = $shop->prices()->whereNotNull('prices')->whereNull('product_id')->where('ignore', false)->orderBy('taobao_id')->get();
		}
		return view('taobao.admin.links', compact('prices', 'shop'));
	}

	public function linked(TaobaoShop $shop)
	{
		$prices = $shop->prices()->whereNotNull('prices')->whereNotNull('product_id')->where('ignore', false)->orderBy('updated_at', 'desc')->get();
		return view('taobao.admin.links', compact('prices', 'shop'));
	}

	public function ignored(TaobaoShop $shop)
	{
		$prices = $shop->prices()->whereNotNull('prices')->where('ignore', true)->get();
		return view('taobao.admin.links', compact('prices', 'shop'));
	}

	public function diffs(TaobaoShop $shop)
	{
		if ($shop->is_partner) {
			$diffs = collect();
			$taobao_prices = $shop->prices()->whereNotNull('product_id')->whereNotNull('prices')->where('ignore', false)->with(['product', 'product.image'])->get();
			$retail_prices = \App\RetailPrice::where('retailer_id', $shop->retailer_id)->orderBy('updated_at')->with(['product', 'product.image'])->get();
			foreach ($retail_prices as $retail) {
				$taobao_price = $taobao_prices->where('product_id', $retail->product_id)->first();
				if (!$taobao_price) {
					// 需要上架
					$diffs->push(['retail' => $retail, 'taobao' => null, 'product' => $retail->product]);
				} elseif(array_keys($retail->prices) != array_keys($taobao_price->prices)) {
					// 需要修改
					$diffs->push(['retail' => $retail, 'taobao' => $taobao_price, 'product' => $retail->product]);
				} else {
					foreach($taobao_price->prices as $size => $price) {
						if($price < $retail->prices[$size] * 1.05 || $price > $retail->prices[$size] * 1.25) {
							$diffs->push(['retail' => $retail, 'taobao' => $taobao_price, 'product' => $retail->product]);
							break;
						}
					}
				}
			}
			foreach ($taobao_prices as $price) {
				if ($retail_prices->where('product_id', $price->product_id)->isEmpty()) {
					// 需要下架
					$diffs->push(['retail' => null, 'taobao' => $price, 'product' => $price->product]);
				}
			}
			return view('taobao.admin.diffs', compact('shop', 'diffs'));
		} else {
			abort(403);
		}
	}

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
}

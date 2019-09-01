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
			$prices = \App\TaobaoPrice::whereNotNull('prices')->whereNull('product_id')->where('ignore', false)->whereHas('taobao_product', function ($query) {
				$query->where('title', 'like', '%off%white%');
			})->orderBy('taobao_id')->limit(30)->get();
		} else {
			$prices = $shop->prices()->whereNotNull('prices')->whereNull('product_id')->where('ignore', false)->whereHas('taobao_product', function ($query) {
				$query->where('title', 'like', '%off%white%');
			})->orderBy('taobao_id')->limit(30)->get();
		}
		return view('taobao.admin.links', compact('prices', 'shop'));
	}

	public function linked(TaobaoShop $shop)
	{
		if (!$shop) {
			$prices = \App\TaobaoPrice::whereNotNull('prices')->whereNotNull('product_id')->where('ignore', false)->orderBy('updated_at', 'desc')->get();
		} else {
			$prices = $shop->prices()->whereNotNull('prices')->whereNotNull('product_id')->where('ignore', false)->orderBy('updated_at', 'desc')->get();
		}
		return view('taobao.admin.links', compact('prices', 'shop'));
	}

	public function ignored(TaobaoShop $shop = null)
	{
		if (!$shop) {
			$prices = \App\TaobaoPrice::whereNotNull('prices')->whereNull('product_id')->where('ignore', true)->whereHas('taobao_product', function ($query) {
				$query->where('title', 'like', '%off%white%');
			})->orderBy('taobao_id')->limit(30)->get();
		} else {
			$prices = $shop->prices()->whereNotNull('prices')->whereNull('product_id')->where('ignore', true)->whereHas('taobao_product', function ($query) {
				$query->where('title', 'like', '%off%white%');
			})->orderBy('taobao_id')->limit(30)->get();
		}
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
				} elseif (array_keys($retail->prices) != array_keys($taobao_price->prices)) {
					// 需要修改
					$diffs->push(['retail' => $retail, 'taobao' => $taobao_price, 'product' => $retail->product]);
				} else {
					foreach ($taobao_price->prices as $size => $price) {
						if ($price < $retail->prices[$size] / 1.15 * 1.05) {
							$diffs->push(['retail' => $retail, 'taobao' => $taobao_price, 'product' => $retail->product]);
							break;
						} elseif ($price > $retail->prices[$size] / 1.15 * 1.35) {
							$diffs->push(['retail' => $retail, 'taobao' => $taobao_price, 'product' => $retail->product]);
							break;
						}
					}
				}
			}
			foreach ($taobao_prices as $price) {
				if ($retail_prices->where('product_id', $price->product_id)->isEmpty()) {
					// 需要下架
					if (!$price->product_id) {
						$price->product_id = null;
						$price->save();
					}
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
			$retail = \App\RetailPrice::firstOrNew([
				'retailer_id' => $price->shop->retailer_id,
				'product_id' => $price->product_id,
			]);
			$prices = $price->prices;
			if ($retail->prices) {
				foreach($retail->prices as $size => $value) {
					if (!array_key_exists($size, $prices) || $value < $prices[$size]) {
						$prices[$size] = $value;
					}
				}
			}
			$retail->prices = $prices;
			$retail->link = $price->url;
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
			$retails = \App\RetailPrice::where('retailer_id', $price->shop->retailer_id)->where('product_id', $price->product_id)->delete();
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
		$price->product_id = null;
		$price->ignore = true;
		$price->save();
		return ['success' => true,];
	}
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
	public function available_functions()
	{
		return [
			'follow_all_retailers' => '关注所有卖家',
			'follow_all_vendors' => '关注所有同行',
			'sync_all_prices' => '同步farfetch, end, taobao价格',
			'update_designer_style_id' => '根据图片名称更新货号',
			'refactor_image_order' => 'refactor_image_order',
		];
	}

	public function index()
	{
		$functions = $this->available_functions();
		return view('admin.index', compact('functions'));
	}

	public function call($function)
	{
		if (array_key_exists($function, $this->available_functions())) {
			$this->$function();
			return redirect(route('admin.index'));
		} else {
			abort(404);
		}
	}

	public function sync_all_prices()
	{
		$this->sync_farfetch();
		$this->sync_end();
		$this->sync_taobao();
	}

	public function sync_farfetch()
	{
		set_time_limit(600);
		$retailer_id = \App\Retailer::where('name', 'Farfetch')->first()->id;
		// delete all retail prices
		\App\RetailPrice::where('retailer_id', $retailer_id)->delete();
		// foreach laravel.product
		foreach (\App\Product::whereNotNull('designer_style_id')->get() as $product) {
			$retail = new \App\RetailPrice([
				'product_id' => $product->id,
				'retailer_id' => $retailer_id,
			]);
			$min_price = INF;
			foreach (\App\FarfetchProduct::where('designer_style_id', $product->designer_style_id)->with('designer')->get() as $farfetch_product) {
				if ($farfetch_product->designer->brand_id !== $product->brand_id) {
					continue;
				}
				$retail->merge($farfetch_product->size_price);
				if ($farfetch_product->price && $farfetch_product->price < $min_price) {
					$min_price = $farfetch_product->price;
					$retail->link = $farfetch_product->url;
				}
			}
			if (!empty($retail->prices)) {
				$retail->save();
			} else {
				$retail->delete();
			}
		}
	}

	public function sync_end()
	{
		set_time_limit(600);
		$retailer_id = \App\Retailer::where('name', 'EndClothing')->first()->id;
		\App\RetailPrice::where('retailer_id', $retailer_id)->delete();
		foreach (\App\Product::whereNotNull('designer_style_id')->get() as $product) {
			$retail = new \App\RetailPrice([
				'product_id' => $product->id,
				'retailer_id' => $retailer_id,
			]);
			$min_price = INF;
			foreach (\App\EndProduct::where('sku', $product->designer_style_id)->with('brand')->get() as $end_product) {
				if ($end_product->brand->id !== $product->brand_id) {
					continue;
				}
				if ($end_product->sizes && $end_product->price) {
					$retail->merge($end_product->size_price);
					if ($end_product->price < $min_price) {
						$min_price = $end_product->price;
						$retail->link = $end_product->url;
					}
				}
			}
			if (!empty($retail->prices)) {
				$retail->save();
			} else {
				$retail->delete();
			}
		}
	}

	public function sync_taobao()
	{
		foreach (\App\TaobaoShop::where('is_partner', false)->get() as $shop) {
			\App\RetailPrice::where('retailer_id', $shop->retailer_id)->delete();
			foreach ($shop->prices()->whereNotNull('product_id')->whereNotNull('prices')->get() as $price) {
				$retail = \App\RetailPrice::firstOrNew(['product_id' => $price->product_id, 'retailer_id' => $shop->retailer_id]);
				$retail->merge($price->prices);
				$retail->link = $price->url;
				$retail->save();
			}
		}
	}

	public function follow_all_retailers()
	{
		$user = auth()->user();
		$user->following_retailers()->sync(\App\Retailer::all());
	}

	public function follow_all_vendors()
	{
		$user = auth()->user();
		$user->following_vendors()->sync(\App\Vendor::all());
	}

	public function update_designer_style_id()
	{
		$products = \App\Product::whereNull('designer_style_id')->whereHas('images', function ($query) {
			$query->where('website_id', 1);
		})->get();
		foreach ($products as $product) {
			$source = $product->images->first()->source;
			$id = substr($source, 6, 20);
			if (preg_match('/^[0-9A-Za-z]+$/', $id)) {
				$product->designer_style_id = strtoupper($id);
				$product->save();
			}
		}
		$products = \App\Product::whereNull('designer_style_id')->whereHas('images', function ($query) {
			$query->where('website_id', 3);
		})->get();
		foreach ($products as $product) {
			$source = $product->images->first()->source;
			$id = substr($source, 0, 20);
			if (preg_match('/^[0-9A-Za-z]+$/', $id)) {
				$product->designer_style_id = strtoupper($id);
				$product->save();
			}
		}
		$products = \App\Product::whereNull('designer_style_id')->whereHas('images', function ($query) {
			$query->where('website_id', 2);
		})->get();
		foreach ($products as $product) {
			$source = $product->images->first()->source;
			$farfetch_id = substr($source, 0, 8);
			if (preg_match('/^[0-9]+$/', $farfetch_id)) {
				$farfetch_product = \App\FarfetchProduct::find($farfetch_id);
				if ($farfetch_product) {
					if ($farfetch_product->designer_style_id) {
						$product->designer_style_id = strtoupper($farfetch_product->designer_style_id);
						$product->save();
					}
				} else {
					$farfetch_product = new \App\FarfetchProduct();
					$farfetch_product->id = $farfetch_id;
					$farfetch_product->save();
				}
			}
		}
	}

	public function refactor_image_order()
	{
		foreach (\App\Product::has('images')->get() as $product) {
			$orders = [];
			foreach($product->images()->orderBy('website_id')->orderBy('order')->get() as $image) {
				if (in_array($image->order, $orders)) {
					$image->order = max($orders) + 1;
					$image->save();
				}
				$orders[] = $image->order;
			}
		}
	}
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
	public function available_functions()
	{
		return [
			'import_all' => '导入farfetch, end商品库',
			'follow_all_retailers' => '关注所有卖家',
			'follow_all_vendors' => '关注所有同行',
			'update_designer_style_id' => '根据图片名称更新货号',
			'convert_webp_to_jpg' => '转换webp格式图片到jpg格式',
			'clear_prices' => '清空所有调货价, 售价',
			'update_prices' => '重新计算调货价, 售价',
			'update_farfetch_prices' => '重新导入Farfetch价格',
			'update_taobao_prices' => '重新导入淘宝价格',
		];
	}

	public function index()
	{
		$functions = $this->available_functions();
		return view('admin.index', compact('functions'));
	}

	public function import_all()
	{
		set_time_limit(600);
		$retailer_id = \App\Retailer::where('name', 'Farfetch')->first()->id;
		$website_id = \App\Website::where('name', 'Farfetch')->first()->id;
		\App\RetailPrice::where('retailer_id', $retailer_id)->delete();
		foreach(\App\Product::whereNotNull('designer_style_id')->get() as $product) {
			$farfetch_prices = [];
			$farfetch_min_price = INF;
			$link = NULL;
			foreach(\App\FarfetchProduct::where('designer_style_id', $product->designer_style_id)->get() as $farfetch_product) {
				if ($farfetch_product->mapped_brand_id !== $product->brand_id) {
					continue;
				}
				if ($farfetch_product->size_price) {
					foreach($farfetch_product->size_price as $size => $price) {
						if (!array_key_exists($size, $farfetch_prices) || $farfetch_prices[$size] > $price) {
							$farfetch_prices[$size] = $price;
						}
						if ($price < $farfetch_min_price) {
							$link = $farfetch_product->url;
						}
					}
				}
				if ($farfetch_product->images->isNotEmpty()) {
					ImageController::import($farfetch_product->images, $product, $website_id);
				}
			}
			if (!empty($farfetch_prices)) {
				\App\RetailPrice::create([
					'product_id' => $product->id,
					'retailer_id' => $retailer_id,
					'prices' =>$farfetch_prices,
					'link' => $link,
				]);
			}
		}
		$retailer_id = \App\Retailer::where('name', 'EndClothing')->first()->id;
		$website_id = \App\Website::where('name', 'EndClothing')->first()->id;
		\App\RetailPrice::where('retailer_id', $retailer_id)->delete();
		foreach(\App\Product::whereNotNull('designer_style_id')->get() as $product) {
			$end_prices = [];
			$end_min_price = INF;
			$link = NULL;
			foreach(\App\EndProduct::where('sku', $product->designer_style_id)->get() as $end_product) {
				if ($end_product->mapped_brand_id !== $product->brand_id) {
					continue;
				}
				if ($end_product->sizes && $end_product->price) {
					foreach(explode(',', $end_product->sizes) as $size) {
						if (!array_key_exists($size, $end_prices) || $end_prices[$size] > $end_product->price) {
							$end_prices[$size] = $end_product->price;
						}
						if ($end_product->price < $end_min_price) {
							$link = $end_product->url;
						}
					}
				}
				if ($end_product->images->isNotEmpty()) {
					ImageController::import($end_product->images, $product, $website_id);
				}
			}
			if (!empty($end_prices)) {
				\App\RetailPrice::create([
					'product_id' => $product->id,
					'retailer_id' => $retailer_id,
					'prices' =>$end_prices,
					'link' => $link,
				]);
			}
		}
		set_time_limit(60);
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
		$products = \App\Product::whereNull('designer_style_id')->whereHas('images', function ($query) { $query->where('website_id', 1); })->get();
		foreach ($products as $product) {
			$source = $product->images->first()->source;
			$id = substr($source, 6, 20);
			if (preg_match('/^[0-9A-Za-z]+$/', $id)) {
				$product->designer_style_id = strtoupper($id);
				$product->save();
			}
		}
		$products = \App\Product::whereNull('designer_style_id')->whereHas('images', function ($query) { $query->where('website_id', 3); })->get();
		foreach ($products as $product) {
			$source = $product->images->first()->source;
			$id = substr($source, 0, 20);
			if (preg_match('/^[0-9A-Za-z]+$/', $id)) {
				$product->designer_style_id = strtoupper($id);
				$product->save();
			}
		}
		$products = \App\Product::whereNull('designer_style_id')->whereHas('images', function ($query) { $query->where('website_id', 2); })->get();
		foreach ($products as $product) {
			$source = $product->images->first()->source;
			$farfetch_id = substr($source, 0, 8);
			if(preg_match('/^[0-9]+$/',$farfetch_id)){
				$farfetch_product = \App\FarfetchProduct::find($farfetch_id);
				if ($farfetch_product) {
					if($farfetch_product->designer_style_id){
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

	public function convert_webp_to_jpg()
	{
		foreach (\App\Image::where('path', 'like', '%.webp')->get() as $image) {
			$i = \Intervention\Image\Facades\Image::make(public_path('storage/' . $image->path));
			if ($i->mime() === "image/webp") {
				$i = $i->save($i->dirname . '/' . $i->filename . '.jpeg', 100);
				if (Storage::exists('public/' . $image->path)) {
					Storage::delete('public/' . $image->path);
				}
				$image->path = str_replace('.webp', '.jpeg', $image->path);
				$image->save();
			}
		}
	}

	public function clear_prices()
	{
		\App\OfferPrice::query()->delete();
		\App\RetailPrice::query()->delete();
		\App\VendorPrice::whereDoesntHave('product')->orWhereDoesntHave('vendor')->delete();
	}

	public function update_prices()
	{
		foreach (\App\VendorPrice::all() as $vendorPrice) {
			$vendorPrice->data = array_map(function ($row) {
				return [
					'size' => $row['size'],
					'cost' => (int) ($row['cost']),
					'offer' => (int) ($row['offer']),
					'retail' => (int) ($row['retail'])
				];
			}, $vendorPrice->data);
			$vendorPrice->save();
		}
	}

	public function update_farfetch_prices()
	{
		$retailer_id = \App\Retailer::where('name', 'Farfetch')->first()->id;
		\App\RetailPrice::where('retailer_id', $retailer_id)->delete();

		foreach (\App\Product::whereNotNull('designer_style_id')->get() as $product) {
			$url = null;
			$size_price = array();
			foreach (\App\FarfetchProduct::whereNotNull('size_price')->where('designer_style_id', $product->designer_style_id)->get() as $farfetch_product) {
				foreach ($farfetch_product->size_price as $size => $price) {
					$price = (int)$price;
					if ((!array_key_exists($size, $size_price)) || ($size_price[$size] > $price)) {
						$size_price[$size] = $price;
						if (min($size_price) == $price) {
							$url = $farfetch_product->url;
						}
					}
				}
			}
			if (empty($size_price)) {
				\App\RetailPrice::where('retailer_id', $retailer_id)->where('product_id', $product->id)->delete();
			} else {
				$retail = \App\RetailPrice::firstOrNew(['retailer_id' => $retailer_id, 'product_id' => $product->id]);
				$retail->prices = $size_price;
				$retail->link = $url;
				$retail->save();
			}
		}
	}

	public function update_taobao_prices()
	{
		foreach (\App\TaobaoShop::where('is_partner', false)->get() as $shop) {
			\App\RetailPrice::where('retailer_id', $shop->retailer_id)->delete();
			foreach ($shop->prices()->whereNotNull('product_id')->whereNotNull('prices')->get() as $price) {
				$retail = \App\RetailPrice::firstOrNew(['product_id' => $price->product_id, 'retailer_id' => $shop->retailer_id]);
				$prices = $price->prices;
				if ($retail->prices) {
					foreach ($retail->prices as $size => $value) {
						if (!array_key_exists($size, $prices) || $value < $prices[$size]) {
							$prices[$size] = $value;
						}
					}
				}
				$retail->prices = $prices;
				$retail->link = $price->url;
				$retail->save();
			}
		}
	}
}

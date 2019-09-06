<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
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

	public function available_functions()
	{
		return [
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
		$products = \App\Product::whereNull('designerStyleId')->whereHas('images', function ($query) { $query->where('website_id', 1); })->get();
		foreach ($products as $product) {
			$source = $product->images->first()->source;
			$id = substr($source, 6, 20);
			if (preg_match('/^[0-9A-Za-z]+$/', $id)) {
				$product->designerStyleId = strtoupper($id);
				$product->save();
			}
		}
		$products = \App\Product::whereNull('designerStyleId')->whereHas('images', function ($query) { $query->where('website_id', 3); })->get();
		foreach ($products as $product) {
			$source = $product->images->first()->source;
			$id = substr($source, 0, 20);
			if (preg_match('/^[0-9A-Za-z]+$/', $id)) {
				$product->designerStyleId = strtoupper($id);
				$product->save();
			}
		}
		$products = \App\Product::whereNull('designerStyleId')->whereHas('images', function ($query) { $query->where('website_id', 2); })->get();
		foreach ($products as $product) {
			$source = $product->images->first()->source;
			$farfetch_id = substr($source, 0, 8);
			if(preg_match('/^[0-9]+$/',$farfetch_id)){
				$farfetch_product = \App\FarfetchProduct::find($farfetch_id);
				if ($farfetch_product) {
					if($farfetch_product->designerStyleId){
						$product->designerStyleId = strtoupper($farfetch_product->designerStyleId);
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
		
		foreach (\App\Product::whereNotNull('designerStyleId')->get() as $product) {
			$url = null;
			$size_price = array();
			foreach (\App\FarfetchProduct::whereNotNull('size_price')->where('designerStyleId', $product->designerStyleId)->get() as $farfetch_product) {
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

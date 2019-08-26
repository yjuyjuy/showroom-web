<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
	public function requests()
	{
		$users = \App\User::where('type', 'pending')->orderBy('updated_at')->get();
		return view('admin.requests', compact('users'));
	}
	public function agree(Request $request)
	{
		$user = \App\User::find($request->validate(['user_id' => 'exists:users,id'])['user_id']);
		$user->is_reseller = true;
		$user->save();
		return ['success'];
	}
	public function reject(Request $request)
	{
		$user = \App\User::find($request->validate(['user_id' => 'exists:users,id'])['user_id']);
		$user->is_rejected = true;
		$user->save();
		return ['success'];
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

	public function available_functions()
	{
		return [
			'follow_all' => '关注所有同行和卖家',
			'update_designer_style_id' => '根据图片名称更新货号',
			'convert_webp_to_jpg' => '转换webp格式图片到jpg格式',
			'clear_prices' => '清空所有调货价, 售价',
			'update_prices' => '重新计算调货价, 售价',
			'update_farfetch_prices' => '重新导入Farfetch价格',
			'update_taobao_prices' => '重新导入淘宝价格',
		];
	}

	public function follow_all()
	{
		$user = auth()->user();
		$user->following_vendors()->sync(\App\Vendor::all());
		$user->following_retailers()->sync(\App\Retailer::all());
	}

	public function update_designer_style_id()
	{
		$products = \App\Product::whereNull('designerStyleId')->with([
			'images' => function ($query) {
				$query->where('website_id', 1);
			},
		])->get()->filter(function ($item) {
			return $item->images->isNotEmpty();
		});
		foreach ($products as $product) {
			$source = $product->images->first()->source;
			$id = substr($source, 6, 20);
			if (preg_match('/^[0-9A-Za-z]+$/', $id)) {
				echo "{$product->name} {$id} <br>";
				$product->designerStyleId = strtoupper($id);
				$product->save();
			}
		}
		$products = \App\Product::whereNull('designerStyleId')->with([
			'images' => function ($query) {
				$query->where('website_id', 3);
			},
		])->get()->filter(function ($item) {
			return $item->images->isNotEmpty();
		});
		foreach ($products as $product) {
			$source = $product->images->first()->source;
			$id = substr($source, 0, 20);
			if (preg_match('/^[0-9A-Za-z]+$/', $id)) {
				echo "{$product->id} {$product->name} {$id} <br>";
				$product->designerStyleId = strtoupper($id);
				$product->save();
			}
		}
		$products = \App\Product::whereNull('designerStyleId')->with([
			'images' => function ($query) {
				$query->where('website_id', 2);
			},
		])->get()->filter(function ($item) {
			return $item->images->isNotEmpty();
		});
		foreach ($products as $product) {
			$source = $product->images->first()->source;
			$farfetch_id = substr($source, 0, 8);
			$farfetch_product = \App\FarfetchProduct::find($farfetch_id);
			if ($farfetch_product) {
				if ($farfetch_product->designerStyleId) {
					$id = $farfetch_product->designerStyleId;
					echo "{$product->id} {$product->name} {$id} <br>";
					$product->designerStyleId = strtoupper($id);
					$product->save();
				}
			} else {
				$farfetch_product = new \App\FarfetchProduct();
				$farfetch_product->id = $farfetch_id;
				$farfetch_product->designer_id = 1205035;
				$farfetch_product->save();
			}
		}
	}

	public function convert_webp_to_jpg()
	{
		foreach (\App\Image::where('path', 'like', '%.webp')->get() as $image) {
			$i = \Intervention\Image\Facades\Image::make(public_path('storage/'.$image->path));
			if ($i->mime() === "image/webp") {
				$i = $i->save($i->dirname.'/'.$i->filename.'.jpeg', 100);
				if (Storage::exists('public/'.$image->path)) {
					Storage::delete('public/'.$image->path);
				}
				$image->path = str_replace('.webp', '.jpeg', $image->path);
				$image->save();
			}
		}
	}

	public function clear_prices()
	{
		\App\OfferPrice::delete();
		\App\RetailPrice::delete();
	}

	public function update_prices()
	{
		foreach (\App\VendorPrice::all() as $vendorPrice) {
			$vendorPrice->data = array_map(function ($row) {
				return [
						'size' => $row['size'],
						'cost' => (int)($row['cost']),
						'offer' => (int)(array_key_exists('offer', $row) ? $row['offer'] : $row['resell']),
						'retail' => (int)($row['retail'])
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
					if ((!array_key_exists($size, $size_price)) || ($size_price[$size] > $price)) {
						$price = (int)ceil($price * 7);
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
		foreach(\App\TaobaoShop::all() as $shop) {
			\App\RetailPrice::where('retailer_id', $shop->retailer_id)->delete();
			foreach($shop->prices()->whereNotNull('product_id')->whereNotNull('prices')->get() as $price) {
				$retail = \App\RetailPrice::firstOrNew(['product_id' => $price->product_id, 'retailer_id' => $shop->retailer_id]);
				$retail->prices = $price->prices;
				$retail->save();
			}
		}
	}
}

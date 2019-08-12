<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
	public function index()
	{
		$this->updatePrices();
		// $this->convertWebpToJpg();
		$this->updateDesignerStyleId();
		$this->migrateFarfetchPrices();
	}

	public function updateDesignerStyleId()
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

	public function convertWebpToJpg()
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

	public function migrateFarfetchPrices()
	{
		$retailer_id = \App\Retailer::where('name', 'Farfetch')->first()->id;
		foreach(\App\Product::whereNotNull('designerStyleId')->get() as $product){
			$url = null;
			$size_price = array();
			foreach(\App\FarfetchProduct::whereNotNull('size_price')->where('designerStyleId',$product->designerStyleId)->get() as $farfetch_product){
				foreach($farfetch_product->size_price as $size => $price){
					if ((!array_key_exists($size, $size_price)) || ($size_price[$size] > $price)) {
						$price = (int)ceil($price * 6.9);
						$size_price[$size] = $price;
						if(min($size_price) == $price){
							$url = $farfetch_product->url;
						}
					}
				}
			}
			if(empty($size_price)){
				\App\RetailPrice::where('retailer_id',$retailer_id)->where('product_id',$product->id)->delete();
			} else {
				$retail = \App\RetailPrice::firstOrNew(['retailer_id' => $retailer_id, 'product_id' => $product->id]);
				$retail->prices = $size_price;
				$retail->link = ['href' => $url];
				$retail->save();
			}
		}
	}

	public function updatePrices()
	{
		\App\OfferPrice::query()->forceDelete();
		\App\RetailPrice::query()->forceDelete();
		foreach(\App\VendorPrice::all() as $vendorPrice){
			$vendorPrice->data = array_map(function($row) {
				return [
					'size' => $row['size'],
					'cost' => (int)($row['cost']),
					'offer' => (int)(array_key_exists('offer',$row) ? $row['offer'] : $row['resell']),
					'retail' => (int)($row['retail'])
				]; }, $vendorPrice->data);
			$vendorPrice->save();
		}
	}
}

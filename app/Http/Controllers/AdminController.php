<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
	public function index()
	{
		$this->convertWebpToJpg();
		$this->updateDesignerStyleId();
	}

	public function updateDesignerStyleId()
	{
		$products = \App\Product::whereNull('designerStyleId')->with([
		// $products = \App\Product::with([
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
				$product->designerStyleId = $id;
				$product->save();
			}
		}
		$products = \App\Product::whereNull('designerStyleId')->with([
		// $products = \App\Product::with([
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
				$product->designerStyleId = $id;
				$product->save();
			}
		}
		$products = \App\Product::whereNull('designerStyleId')->with([
		// $products = \App\Product::with([
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
			if($farfetch_product){
				if($farfetch_product->designerStyleId){
					$id = $farfetch_product->designerStyleId;
					echo "{$product->id} {$product->name} {$id} <br>";
					$product->designerStyleId = $id;
					$product->save();
				}
			} else {
				$farfetch_product = new \App\FarfetchProduct();
				$farfetch_product->id = $farfetch_id;
				$farfetch_product->designer_id = 1205035;
				$farfetch_product->save();
			}
		}
		return;
	}

	public function convertWebpToJpg()
	{
		foreach(\App\Image::where('path','like','%.webp')->get() as $image)
		{
			$i = \Intervention\Image\Facades\Image::make(public_path('storage/'.$image->path));
			if($i->mime() === "image/webp"){
				$i = $i->save($i->dirname.'/'.$i->filename.'.jpeg',100);
				if (Storage::exists('public/'.$image->path)) {
					Storage::delete('public/'.$image->path);
				}
				$image->path = str_replace('.webp','.jpeg',$image->path);
				$image->save();
			}
		}
	}
}

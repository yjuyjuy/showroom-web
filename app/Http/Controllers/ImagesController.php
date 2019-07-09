<?php

namespace App\Http\Controllers;

use App\Image;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class ImagesController extends Controller
{
	public function store()
	{
		request()->validate([
			'product_id' => ['required','exists:products,id'],
			'website_id' => ['required',Rule::in(Arr::pluck($this->websites(), 'id'))],
			'type_id' => ['required',Rule::in(Arr::pluck($this->types(), 'id'))],
			'image' => ['required','file','image','max:10000'],
		]);
		$path = request('image')->store('images/'.request('product_id'), 'public');
		\Intervention\Image\Facades\Image::make(public_path("storage/{$path}"))->fit(1000, 1413)->save();
		\App\Image::create([
			'path' => $path,
			'source' => request('image')->getClientOriginalName(),
			'product_id' => request('product_id'),
			'website_id' => request('website_id'),
			'type_id' => request('type_id'),
		]);
		return ['success'];
	}

	public function edit(Product $product)
	{
		$images = $product->images()->orderBy('website_id', 'asc')->orderBy('type_id', 'asc')->get();
		$images = $images->mapToGroups(function ($item, $key) {
			return [$item->website_id => $item];
		});
		$types = $this->types();
		$websites = collect($this->websites());
		return view('images.edit', compact('product', 'images', 'types', 'websites'));
	}

	public function update(Image $image)
	{
		request()->validate([
			'image' => ['required','file','image','max:10000'],
		]);
		if (Storage::exists('public/'.$image->path)) {
			Storage::delete('public/'.$image->path);
		}
		$path = request('image')->store("images/{$image->product_id}", 'public');
		\Intervention\Image\Facades\Image::make(public_path("storage/{$path}"))->fit(1000, 1413)->save();
		\App\Image::create([
			'path' => $path,
			'source' => request('image')->getClientOriginalName(),
			'product_id' => $image->product_id,
			'website_id' => $image->website_id,
			'type_id' => $image->type_id,
		]);
		$image->delete();
		return ['success'];
	}

	public function move(Image $image)
	{
		request()->validate([
			'website_id' => ['required',Rule::in(Arr::pluck($this->websites(), 'id'))],
			'type_id' => ['required',Rule::in(Arr::pluck($this->types(), 'id'))],
		]);
		$image->update([
			'website_id' => request('website_id'),
			'type_id' => request('type_id'),
		]);
		return ['success'];
	}

	public function swap()
	{
		request()->validate([
			'image_id1' => ['required', 'exists:images,id'],
			'image_id2' => ['required', 'exists:images,id'],
		]);
		$image1 = Image::find(request('image_id1'));
		$image2 = Image::find(request('image_id2'));

		$website_id = $image1->website_id;
		$type_id = $image1->type_id;
		$image1->website_id = $image2->website_id;
		$image1->type_id = $image2->type_id;
		$image2->website_id = $website_id;
		$image2->type_id = $type_id;

		$image1->save();
		$image2->save();
	}

	public function destroy(Image $image)
	{
		if (Storage::exists('public/'.$image->path)) {
			Storage::delete('public/'.$image->path);
		}
		$image->delete();
	}

	public function types()
	{
		return [
			['id' => 1,'name' => 'front','name_cn' => '正面','angle' => 'front'],
			['id' => 2,'name' => 'front-angled','name_cn' => '正侧面','angle' => 'front'],
			['id' => 3,'name' => 'back','name_cn' => '背面','angle' => 'back'],
			['id' => 4,'name' => 'back-angled','name_cn' => '背侧面','angle' => 'back'],
			['id' => 5,'name' => 'detail1','name_cn' => '细节1','angle' => 'close-up'],
			['id' => 6,'name' => 'detail2','name_cn' => '细节2','angle' => 'close-up'],
			['id' => 7,'name' => 'detail3','name_cn' => '细节3','angle' => 'close-up'],
			['id' => 8,'name' => 'detail4','name_cn' => '细节4','angle' => 'close-up'],
			['id' => 9,'name' => 'pose1','name_cn' => '全身1','angle' => 'front'],
			['id' => 10,'name' => 'pose2','name_cn' => '全身2','angle' => 'back'],
			['id' => 11,'name' => 'cover1','name_cn' => '正面平铺','angle' => 'front'],
			['id' => 12,'name' => 'cover1','name_cn' => '反面平铺','angle' => 'back'],
			['id' => 14,'name' => 'side','name_cn' => '侧面','angle' => 'back'],
		];
	}
	public function websites()
	{
		return [
			['id' => 1,'name' => 'off---white'],
			['id' => 2,'name' => 'farfetch'],
			['id' => 3,'name' => 'antonioli'],
			['id' => 4,'name' => 'Dopebxtch'],
			['id' => 5,'name' => 'ssense'],
			['id' => 6,'name' => 'endclothing'],
			['id' => 7,'name' => 'selfridges'],
			['id' => 8,'name' => 'matchesfashion'],
			['id' => 9,'name' => 'luisaviaroma'],
			['id' => 10,'name' => 'vrient'],
			['id' => 11,'name' => 'lindelepalais'],
			['id' => 12,'name' => 'revolve'],
		];
	}
}

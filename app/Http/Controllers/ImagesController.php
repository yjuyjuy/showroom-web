<?php

namespace App\Http\Controllers;

use App\Image;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImagesController extends Controller
{
	public function store()
	{
		request()->validate([
			'product_id' => ['required','exists:products,id'],
			'website_id' => ['required','exists:websites,id'],
			'type_id' => ['required','exists:types,id'],
			'image' => ['required','file','image','max:10000'],
		]);
		$path = request('image')->store('images/1101191001', 'public');
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
		$types = \App\Type::all();
		$websites = \App\Website::all();
		return view('images.edit', compact('product', 'images', 'types', 'websites'));
	}

	public function update(Image $image)
	{
		request()->validate([
			'image' => ['required','file','image','max:10000'],
		]);
		Storage::delete('public/'.$image->path);
		$path = request('image')->store("images/{$image->product->id}", 'public');
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
			'website_id' => ['required','exists:websites,id'],
			'type_id' => ['required','exists:types,id'],
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
		Storage::delete('public/'.$image->path);
		$image->delete();
	}
}

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
			'image' => ['required','image'],
		]);
		$filename = sprintf("%s_%02d_%02d.%s", request('product_id'), request('website_id'), request('type_id'), request('image')->extension());
		$imagePath = request('image')->storeAs('images', $filename, 'public');
		\Intervention\Image\Facades\Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1413)->save();
		\App\Image::create([
			'filename' => $filename,
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
			'image' => ['required','image'],
		]);
		$filename = $image->filename;
		Storage::delete('/public/images/'.$filename);
		$imagePath = request('image')->storeAs('images', $filename, 'public');
		\Intervention\Image\Facades\Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1413)->save();
		$image->save();
		return ['success'];
	}

	public function move(Image $image)
	{
		request()->validate([
			'website_id' => ['required','exists:websites,id'],
			'type_id' => ['required','exists:types,id'],
		]);
		$ext = substr($image->filename, strrpos($image->filename, '.')+1);
		$newFilename = sprintf("%s_%02d_%02d.%s", $image->product_id, request('website_id'), request('type_id'), $ext);
		Storage::delete('/public/images/'.$newFilename);
		Storage::move('/public/images/'.$image->filename, '/public/images/'.$newFilename);
		$image->update([
			'website_id' => request('website_id'),
			'type_id' => request('type_id'),
			'filename' => $newFilename,
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
		$ext1 = substr($image1->filename, strrpos($image1->filename, '.')+1);
		$ext2 = substr($image2->filename, strrpos($image2->filename, '.')+1);

		$filename2 = sprintf("%s_%02d_%02d.%s", $image2->product_id, $image2->website_id, $image2->type_id, $ext1);
		$filename1 = sprintf("%s_%02d_%02d.%s", $image1->product_id, $image1->website_id, $image1->type_id, $ext2);

		Storage::delete('/public/images/'.$filename2.'.new');
		Storage::move('/public/images/'.$image1->filename, '/public/images/'.$filename2.'.new');
		Storage::delete('/public/images/'.$filename1.'.new');
		Storage::move('/public/images/'.$image2->filename, '/public/images/'.$filename1.'.new');

		Storage::delete('/public/images/'.$filename1);
		Storage::move('/public/images/'.$filename1.'.new', '/public/images/'.$filename1);
		Storage::delete('/public/images/'.$filename2);
		Storage::move('/public/images/'.$filename2.'.new', '/public/images/'.$filename2);

		$image1->filename = $filename1;
		$image1->save();
		$image2->filename = $filename2;
		$image2->save();
	}

	public function destroy(Image $image)
	{
		Storage::delete('/public/images/'.$image->filename);
		$image->delete();
	}
}

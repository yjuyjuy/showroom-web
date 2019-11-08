<?php

namespace App\Http\Controllers;

use App\Image;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class ImageController extends Controller
{
	public function store()
	{
		request()->validate([
			'product_id' => ['required','exists:products,id'],
			'image' => ['required_without:images','file','mimetypes:image/*','max:10000'],
			'images.*' => ['required_without:image','file','mimetypes:image/*','max:10000'],
			'order' => ['required_with:image','numeric', 'min:1'],
		]);
		if (request('images')) {
			$order = \App\Product::find(request('product_id'))->images()->max('order');
			foreach (request('images') as $uploadedFile) {
				$order += 1;
				\Intervention\Image\Facades\Image::make($uploadedFile->path())->fit(1000, 1413)->save(null, 100, 'jpg');
				$path = $uploadedFile->store('images/'.request('product_id'), 'public');
				\Intervention\Image\Facades\Image::make(public_path('storage/'.$path))->fit(400, 565)->save(public_path('storage/'.$path.'_400.jpeg'), 80);
				\Intervention\Image\Facades\Image::make(public_path('storage/'.$path))->fit(800, 1130)->save(public_path('storage/'.$path.'_800.jpeg'), 80);
				\App\Image::create([
					'path' => $path,
					'source' => $uploadedFile->getClientOriginalName(),
					'product_id' => request('product_id'),
					'order' => $order,
				]);
				`~/rsync.sh ${path} &>/dev/null &`;
			}
		} else {
			\Intervention\Image\Facades\Image::make(request('image')->path())->fit(1000, 1413)->save(null, 100, 'jpg');
			$path = request('image')->store('images/'.request('product_id'), 'public');
			\Intervention\Image\Facades\Image::make(public_path('storage/'.$path))->fit(400, 565)->save(public_path('storage/'.$path.'_400.jpeg'), 80);
			\Intervention\Image\Facades\Image::make(public_path('storage/'.$path))->fit(800, 1130)->save(public_path('storage/'.$path.'_800.jpeg'), 80);
			\App\Image::create([
				'path' => $path,
				'source' => request('image')->getClientOriginalName(),
				'product_id' => request('product_id'),
				'order' => request('order'),
			]);
			`~/rsync.sh ${path} &>/dev/null &`;
		}
		return ['success'];
	}

	public function edit(Product $product)
	{
		$images = $product->images()->get();
		return view('images.edit', compact('product', 'images'));
	}

	public function update(Image $image)
	{
		request()->validate([
			'image' => ['required','file','mimetypes:image/*','max:10000'],
		]);
		if (Storage::exists('public/'.$image->path)) {
			Storage::delete('public/'.$image->path);
		}
		\Intervention\Image\Facades\Image::make(request('image')->path())->fit(1000, 1413)->save(null, 100, 'jpg');
		$path = request('image')->store("images/{$image->product_id}", 'public');
		\Intervention\Image\Facades\Image::make(public_path('storage/'.$path))->fit(400, 565)->save(public_path('storage/'.$path.'_400.jpeg'), 80);
		\Intervention\Image\Facades\Image::make(public_path('storage/'.$path))->fit(800, 1130)->save(public_path('storage/'.$path.'_800.jpeg'), 80);
		\App\Image::create([
			'path' => $path,
			'source' => request('image')->getClientOriginalName(),
			'product_id' => $image->product_id,
			'order' => $image->order,
		]);
		`~/rsync.sh ${path} &>/dev/null &`;
		$image->delete();
		return ['success'];
	}

	public function move(Image $image)
	{
		request()->validate([
			'order' => ['required','numeric', 'min:1'],
		]);
		$image->update([
			'order' => request('order'),
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

		$buff = $image1->order;
		$image1->order = $image2->order;
		$image2->order = $buff;

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

	public function import($images, $product)
	{
		$order = $product->images()->max('order');
		foreach ($images as $image) {
			$original_filename = explode('?', array_reverse(explode('/', $image->url))[0])[0];
			if (!$product->images()->where('source', $original_filename)->first()) {
				$order += 1;
				$path = 'images/'.$product->id.'/'.bin2hex(random_bytes(20)).'.jpeg';
				$dir = dirname('storage/'.$path);
				if (!is_dir($dir)) {
					mkdir($dir, 0777, true);
				}
				if ($image->path && Storage::exists('public/'.$image->path)) {
					try {
						Storage::copy('public/'.$image->path, 'public/'.$path);
					} catch (Exception $e) {
						return;
					}
				} else {
					try {
						$f = fopen($image->url, 'r');
						file_put_contents('storage/'.$path, $f);
						fclose($f);
					} catch (Exception $e) {
						return;
					}
				}
				\Intervention\Image\Facades\Image::make('storage/'.$path)->fit(400, 565)->save('storage/'.$path.'_400.jpeg', 80);
				\Intervention\Image\Facades\Image::make('storage/'.$path)->fit(800, 1130)->save('storage/'.$path.'_800.jpeg', 80);
				\App\Image::create([
					'path' => $path,
					'source' => $original_filename,
					'product_id' => $product->id,
					'order' => $order,
				]);
			}
		}
	}
}

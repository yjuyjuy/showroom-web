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
		umask(0);
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
				$path = $uploadedFile->store('images/'.request('product_id'), 'public');
				\App\Jobs\OptimizeImage::dispatch($path);
				\App\Image::create([
					'path' => $path,
					'source' => $uploadedFile->getClientOriginalName(),
					'product_id' => request('product_id'),
					'order' => $order,
				]);
			}
		} else {
			$path = request('image')->store('images/'.request('product_id'), 'public');
			\App\Jobs\OptimizeImage::dispatch($path);
			\App\Image::create([
				'path' => $path,
				'source' => request('image')->getClientOriginalName(),
				'product_id' => request('product_id'),
				'order' => request('order'),
			]);
		}
		return ['success'];
	}

	public function edit(Product $product)
	{
		$user = auth()->user();
		$images = $product->images()->get();
		return view('images.edit', compact('product', 'images', 'user'));
	}

	public function update(Image $image)
	{
		umask(0);
		request()->validate([
			'image' => ['required','file','mimetypes:image/*','max:10000'],
		]);
		$this->destroy($image);
		$path = request('image')->store("images/{$image->product_id}", 'public');
		\App\Jobs\OptimizeImage::dispatch($path);
		\App\Image::create([
			'path' => $path,
			'source' => request('image')->getClientOriginalName(),
			'product_id' => $image->product_id,
			'order' => $image->order,
		]);
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
		foreach(['', '_upsized.jpeg', '_400.jpeg', '_800.jpeg'] as $suffix) {
			if (Storage::exists('public/'.$image->path.$suffix)) {
				Storage::delete('public/'.$image->path.$suffix);
			}
		}
		$image->delete();
	}

	public function import($images, $product)
	{
		umask(0);
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
					} catch (\Throwable $e) {
						return;
					}
				} elseif ($image->url) {
					try {
						$f = fopen($image->url, 'r');
						file_put_contents('storage/'.$path, $f);
						fclose($f);
					} catch (\Throwable $e) {
						return;
					}
				}
				\App\Jobs\OptimizeImage::dispatch($path);
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

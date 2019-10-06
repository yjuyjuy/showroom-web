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
			'website_id' => ['required','exists:websites,id'],
			'image' => ['required_without:images','file','mimetypes:image/*','max:10000'],
			'images.*' => ['required_without:image','file','mimetypes:image/*','max:10000'],
			'order' => ['required_with:image','numeric', 'min:1'],
		]);
		if (request('images')) {
			$order = \App\Product::find(request('product_id'))->images->where('website_id', request('website_id'))->max('order');
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
					'website_id' => request('website_id'),
					'order' => $order,
				]);
			}
		} else {
			\Intervention\Image\Facades\Image::make(request('image')->path())->fit(1000, 1413)->save(null, 100, 'jpg');
			$path = request('image')->store('images/'.request('product_id'), 'public');
			\Intervention\Image\Facades\Image::make(public_path('storage/'.$path))->fit(400, 565)->save(public_path('storage/'.$path.'_400.jpeg'), 80);
			\Intervention\Image\Facades\Image::make(public_path('storage/'.$path))->fit(200, 283)->save(public_path('storage/'.$path.'_200.jpeg'), 80);
			\App\Image::create([
				'path' => $path,
				'source' => request('image')->getClientOriginalName(),
				'product_id' => request('product_id'),
				'website_id' => request('website_id'),
				'order' => request('order'),
			]);
		}
		return ['success'];
	}

	public function edit(Product $product)
	{
		$images = $product->images()->orderBy('website_id', 'asc')->orderBy('order')->get();
		$images = $images->mapToGroups(function ($item, $key) {
			return [$item->website_id => $item];
		});
		$websites = \App\Website::all();
		return view('images.edit', compact('product', 'images', 'websites'));
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
		\Intervention\Image\Facades\Image::make(public_path('storage/'.$path))->fit(200, 283)->save(public_path('storage/'.$path.'_200.jpeg'), 80);
		\App\Image::create([
			'path' => $path,
			'source' => request('image')->getClientOriginalName(),
			'product_id' => $image->product_id,
			'website_id' => $image->website_id,
			'order' => $image->order,
		]);
		$image->delete();
		return ['success'];
	}

	public function move(Image $image)
	{
		request()->validate([
			'website_id' => ['required','exists:websites,id'],
			'order' => ['required','numeric', 'min:1'],
		]);
		$image->update([
			'website_id' => request('website_id'),
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

		$website_id = $image1->website_id;
		$buff = $image1->order;
		$image1->website_id = $image2->website_id;
		$image1->order = $image2->order;
		$image2->website_id = $website_id;
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
	public function websites()
	{
		return [
			['id' => 1,		'name' => 'off---white'],
			['id' => 2,		'name' => 'farfetch'],
			['id' => 3,		'name' => 'antonioli'],
			['id' => 4,		'name' => 'Dopebxtch'],
			['id' => 5,		'name' => 'ssense'],
			['id' => 6,		'name' => 'endclothing'],
			['id' => 7,		'name' => 'selfridges'],
			['id' => 8,		'name' => 'matchesfashion'],
			['id' => 9,		'name' => 'luisaviaroma'],
			['id' => 10,	'name' => 'vrient'],
			['id' => 11,	'name' => 'lindelepalais'],
			['id' => 12,	'name' => 'revolve'],
		];
	}
	public static function import($images, $product, $website_id)
	{
		$order = $product->images()->where('website_id', $website_id)->max('order');
		foreach ($images as $image) {
			$original_filename = explode('?', array_reverse(explode('/', $image->url))[0])[0];
			if (!$product->images()->where('website_id', $website_id)->where('source', $original_filename)->first()) {
				$order += 1;
				$path = 'images/'.$product->id.'/'.bin2hex(random_bytes(20)).'.jpeg';
				if (!is_dir(dirname('storage/'.$path))) {
					mkdir(dirname('storage/'.$path), 0777, true);
				}
				if ($image->path) {
					Storage::copy('storage/'.$image->path, 'storage/'.$path);
				} else {
					$f = fopen($image->url, 'r');
					file_put_contents('storage/'.$path, $f);
					fclose($f);
				}
				\Intervention\Image\Facades\Image::make('storage/'.$path)->fit(400, 565)->save('storage/'.$path.'_400.jpeg', 80);
				\Intervention\Image\Facades\Image::make('storage/'.$path)->fit(800, 1130)->save('storage/'.$path.'_800.jpeg', 80);
				\App\Image::create([
					'path' => $path,
					'source' => $original_filename,
					'product_id' => $product->id,
					'website_id' => $website_id,
					'order' => $order,
				]);
			}
		}
	}
}

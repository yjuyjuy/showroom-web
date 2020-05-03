<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Retailer;
use App\Image;
use App\Jobs\OptimizeProfileImage;

class RetailerController extends Controller
{
	public function update(Request $request) {
		$user = auth()->user();
		if ($user->is_admin && $request->input('retailer_id')) {
			$retailer = Retailer::find($request->input('retailer_id'));
		} else {
			$retailer = $user->vendor->retailer ?? null;
		}
		if (!$retailer) return 'Unknown retailer';
		$data = $request->validate([
			'image' => ['sometimes', 'file','mimetypes:image/*','max:10000'],
			'name' => ['sometimes', 'string', 'max:255', 'unique:retailers'],
			'homepage' => ['sometimes', 'string', 'email', 'max:255', 'unique:retailers'],
		]);
		if (array_key_exists('image', $data)) {
			if ($retailer->image) {
				(new \App\Http\Controllers\ImageController())->destroy($retailer->image);
			}
			$path = $data['image']->store('profiles', 'public');
			OptimizeProfileImage::dispatch($path);
			$image = Image::create([
				'path' => $path,
				'source' => $data['image']->getClientOriginalName(),
				'user_id' => $user->id,
				'order' => 1,
			]);
			$retailer->image_id = $image->id;
		}
		if (array_key_exists('name', $data)) {
			$retailer->name = $data['name'];
		}
		if (array_key_exists('homepage', $data)) {
			$retailer->homepage = $data['homepage'];
		}
		$retailer->save();
		return $retailer->fresh()->loadMissing('image');
	}
}

<?php

namespace App\Http\Controllers\api\v3\seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Vendor;
use App\Image;
use App\Jobs\OptimizeProfileImage;

class VendorController extends Controller
{
	public function index()
	{
		return [
			'vendors' => Vendor::with('image')->get(),
		];
	}
	public function show(Vendor $vendor)
	{
		return $vendor;
	}
	public function follow($vendorId)
	{
		if (Vendor::find($vendorId)) {
			auth()->user()->following_vendors()->syncWithoutDetaching($vendorId);
		}
		return $this->following();
	}
	public function unfollow($vendorId)
	{
		if (Vendor::find($vendorId)) {
			auth()->user()->following_vendors()->detach($vendorId);
		}
		return $this->following();
	}
	public function following()
	{
		return [
			'following_vendors' => auth()->user()->following_vendors()->pluck('vendor_id'),
		];
	}
	public function update(Request $request)
	{
		$user = auth()->user();
		if ($user->is_admin && $request->input('vendor_id')) {
			$vendor = Vendor::find($request->input('vendor_id'));
		} else {
			$vendor = $user->vendor;
		}
		if (!$vendor) {
			return 'Unknown vendor';
		}
		$data = $request->validate([
			'image' => ['sometimes', 'file', 'mimetypes:image/*', 'max:10000'],
			'name' => ['sometimes', 'string', 'max:255', 'unique:vendors'],
			'wechat_id' => ['sometimes', 'string', 'email', 'max:255', 'unique:vendors'],
			'city' => ['sometimes', 'string', 'max:255'],
		]);
		if (array_key_exists('image', $data)) {
			if ($vendor->image) {
				(new \App\Http\Controllers\ImageController())->destroy($vendor->image);
			}
			$path = $data['image']->store('profiles', 'public');
			OptimizeProfileImage::dispatch($path);
			$image = Image::create([
				'path' => $path,
				'source' => $data['image']->getClientOriginalName(),
				'user_id' => $user->id,
				'order' => 1,
			]);
			$vendor->image_id = $image->id;
		}
		if (array_key_exists('name', $data)) {
			$vendor->name = $data['name'];
		}
		if (array_key_exists('wechat_id', $data)) {
			$vendor->wechat_id = $data['wechat_id'];
		}
		if (array_key_exists('city', $data)) {
			$vendor->city = $data['city'];
		}
		$vendor->save();
		return $vendor->fresh()->loadMissing('image');
	}
}

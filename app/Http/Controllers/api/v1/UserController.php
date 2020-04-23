<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Image;

class UserController extends Controller
{
  public function show() {
		return [
			'user' => auth()->user()->load(['vendor', 'image']),
			'token' => auth()->user()->token(),
		];
	}

	public function update(Request $request) {
		$data = $request->validate([
			'image' => ['sometimes', 'file','mimetypes:image/*','max:10000'],
			'username' => ['sometimes', 'string', 'max:255', 'unique:users'],
			'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users'],
			'old_password' => ['sometimes', 'required_with:new_password', 'string'],
			'new_password' => ['sometimes', 'string', 'min:8', 'confirmed'],
		]);
		if (array_key_exists('image', $data)) {
			$path = $data['image']->store('profiles', 'public');
			\App\Jobs\OptimizeProfileImage::dispatch($path);
			\App\Image::create([
				'path' => $path,
				'source' => $data['image']->getClientOriginalName(),
				'user_id' => auth()->user()->id,
				'order' => 1,
			]);
		}
		if (array_key_exists('username', $data)) {
			$user = auth()->user();
			$user->username = $data['username'];
			$user->save();
		}
		if (array_key_exists('email', $data)) {
			$user = auth()->user();
			$user->email = $data['email'];
			$user->save();
		}
		if (array_key_exists('new_password', $data)) {
			$user = auth()->user();
			if (Hash::check($data['old_password'], $user->makeVisible(['password'])->password)) {
				$user->password = Hash::make($data['new_password']);
				$user->save();
			} else {
				return ['old_password' => '密码验证失败'];
			}
		}
		return $this->show();
	}
}

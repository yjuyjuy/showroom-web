<?php

namespace App\Http\Controllers\api\v2\retail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\InviteCode;

class RegisterController extends Controller
{
    public function handle(Request $request)
		{
			$data = $request->validate([
				'username' => ['required', 'string', 'max:255', 'unique:users'],
				'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
				'password' => ['required', 'string', 'min:8', 'confirmed'],
				'invite_code' => ['nullable', 'string'],
			]);
			do {
				$id = random_int(1000000000, 9999999999);
			} while(User::find($id));
			$type = NULL;
			if ($data['invite_code']) {
				$code = InviteCode::find($data['invite_code']);
				if ($code) {
					$type = 'invited:'.$code->vendor->id;
				}
			}
			return User::create([
				'id' => $id,
				'email' => $data['email'],
				'username' => $data['username'],
				'password' => Hash::make($data['password']),
				'type' => $type,
			]);
		}
}

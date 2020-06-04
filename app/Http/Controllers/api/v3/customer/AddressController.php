<?php

namespace App\Http\Controllers\api\v3\customer;

use App\Address;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddressController extends Controller
{
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$data = $this->validateRequest();
		$data['user_id'] = auth()->user()->id;
		return Address::create($data);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Address  $address
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Address $address)
	{
		$address->update($this->validateRequest());
		return $address->fresh();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Address  $address
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Address $address)
	{
		return ['success' => $address->delete()];
	}

	public function validateRequest()
	{
		$data = request()->validate([
			'name' => 'nullable|string',
			'phone' => 'nullable|string',
			'address1' => 'nullable|string',
			'address2' => 'nullable|string',
			'city' => 'nullable|string',
			'state' => 'nullable|string',
			'country' => 'nullable|string',
			'zip' => 'nullable|string',
		]);
		$data['name'] = $data['name'] ?? '';
		$data['phone'] = $data['phone'] ?? '';
		$data['address1'] = $data['address1'] ?? '';
		$data['address2'] = $data['address2'] ?? '';
		$data['city'] = $data['city'] ?? '';
		$data['state'] = $data['state'] ?? '';
		$data['country'] = $data['country'] ?? '';
		$data['zip'] = $data['zip'] ?? '';
		return $data;
	}
}

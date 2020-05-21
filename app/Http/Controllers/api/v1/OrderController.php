<?php

namespace App\Http\Controllers\api\v1;

use App\Order;
use App\Address;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$data = $request->validate([
			'product_id' => 'required|exists:products,id',
			'vendor_id' => 'required|exists:vendors,id',
			'size' => 'required|string',
			'is_direct' => 'required|boolean',
			'address_id' => 'required|exists:addresses,id',
		]);
		$address = Address::find($data['address_id']);
		unset($data['address_id']);
		do {
			$id = strtr(rtrim(base64_encode(random_bytes(3)), '='), '+/', '-_');
		} while (Order::find($id));
		$data = array_merge([
			'id' => $id,
			'user_id' => auth()->user()->id,
			'name' => $address->name,
			'phone' => $address->phone,
			'address1' => $address->address1,
			'address2' => $address->address2,
			'city' => $address->city,
			'state' => $address->state,
			'country' => $address->country,
			'zip' => $address->zip,
			'status' => 'created',
		], $data);
		return Order::create($data)->load([
			'product', 'product.images', 'product.season', 'product.color', 'product.brand', 'vendor',
		]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Order  $order
	 * @return \Illuminate\Http\Response
	 */
	public function show(Order $order)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Order  $order
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Order $order)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Order  $order
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Order $order)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Order  $order
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Order $order)
	{
		//
	}
}

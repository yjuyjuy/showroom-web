<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Order;
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
			'productId' => 'required|exists:products,id',
			'vendorId' => 'required|exists:vendors,id',
			'size' => 'required|string',
			'is_direct' => 'required|boolean',
		]);
		do {
			$id = strtr(rtrim(base64_encode(random_bytes(3)), '='), '+/', '-_');
		} while (Order::find($id));
		$data = array_merge($data, (new AddressController())->validateRequest(), [
			'id' => $id,
			'user_id' => auth()->user()->id,
			'status' => 'created',
		]);
		return Order::create($data)->load(['product', 'vendor']);
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

<?php

namespace App\Http\Controllers\api\v1;

use App\Order;
use App\Product;
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
			'quantity' => 'required|integer|min:1',
			'price' => 'required|numeric',
			'shipping' => 'required|numeric',
			'insurance' => 'required|numeric',
			'total' => 'required|numeric',
			'is_direct' => 'required|boolean',
			'address_id' => 'required|exists:addresses,id',
		]);
		$price = Product::find($data['product_id'])->offers()->where('vendor_id', $data['vendor_id'])->first();
		$data['size'] = (string)$data['size'];
		if (!array_key_exists($data['size'], $price)) {
			return ['message' => $data['size']." size is not available"];
		}
		$data['quantity'] = (int)$data['quantity'];
		$price = $price[$data['size']];
		if ($price != $data['price']) {
			return ['message' => 'Prices don\'t match'];
		}
		
		$shipping = 23;
		if ($shipping != $data['shipping']) {
			return ['message' => 'Shipping prices don\'t match'];
		}

		if ($price <= 500) {
			$insurance = 1;
		} elseif ($price <= 1000) {
			$insurance = 2;
		} else {
			$insurance = round($price * 0.5) / 100;
		}
		if ($insurance != $data['insurance']) {
			return ['message' => 'Insurance prices don\'t match'];
		}

		$total = round(($data['quantity'] * $price + $shipping + $insurance) * 100) / 100;
		if ($total != $data['total']) {
			return ['message' => 'Total prices don\'t match'];
		}
		
		$address = Address::find($data['address_id']);
		unset($data['address_id']);

		do {
			$id = strtr(rtrim(base64_encode(random_bytes(3)), '='), '+/', '-_');
		} while (Order::find($id));
		
		$data = array_merge($data, [
			'id' => $id,
			'user_id' => auth()->user()->id,
			'shipping' => $shipping,
			'insurance' => $insurance,
			'total' => $total,
			'name' => $address->name,
			'phone' => $address->phone,
			'address1' => $address->address1,
			'address2' => $address->address2,
			'city' => $address->city,
			'state' => $address->state,
			'country' => $address->country,
			'zip' => $address->zip,
			'status' => 'created',
		]);
		return $this->show(Order::create($data));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Order  $order
	 * @return \Illuminate\Http\Response
	 */
	public function show(Order $order)
	{
		return $order->load(['product', 'product.brand', 'product.color', 'product.season', 'product.images', 'vendor',]);
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

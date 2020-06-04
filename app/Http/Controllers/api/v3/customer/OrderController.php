<?php

namespace App\Http\Controllers\api\v3\customer;

use App\Order;
use App\Vendor;
use App\Product;
use App\Address;
use App\Retailer;
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
		$ITEMS_PER_PAGE = 12;

		$query = auth()->user()->orders()->orderByDesc('created_at');

		if (($status = request()->input('status')) && in_array($status, ['created', 'confirmed', 'paid', 'shipped'])) {
			$query->where('status', $status);
		}
		
		$total_pages = ceil($query->count() / $ITEMS_PER_PAGE);
		$page = min(max(request()->query('page', 1), 1), $total_pages);
		$orders = $query->forPage($page, $ITEMS_PER_PAGE)->get();

		return [
			'orders' => $orders->load(['product', 'product.brand', 'product.season', 'product.color', 'product.image', 'seller']),
			'page' => $page,
			'total_pages' => $total_pages,
		];
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
			'seller_id' => 'required|exists:retailers,id',
			'size' => 'required|string',
			'quantity' => 'required|integer|min:1',
			'price' => 'required|numeric',
			'shipping' => 'required|numeric',
			'insurance' => 'required|numeric',
			'total' => 'required|numeric',
			'is_direct' => 'required|boolean',
			'address_id' => 'required|exists:addresses,id',
		]);
		$retailer = Retailer::find($data['seller_id']);
		unset($data['seller_id']);
		$data['seller_type'] = Retailer::class;
		$data['seller_id'] = $retailer->id;

		$retail = Product::find($data['product_id'])->retails()->where('retailer_id', $retailer->id)->first();
		if (!array_key_exists($data['size'], $retail->prices)) {
			return ['message' => $data['size']." size is not available"];
		}
		$price = $retail->prices[$data['size']];
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
			$id = strtr(rtrim(base64_encode(random_bytes(6)), '='), '+/', '-_');
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
		$order->refresh();
		$user = auth()->user();
		return $order->loadMissing(['product', 'product.brand', 'product.color', 'product.season', 'product.image', 'seller',]);
	}

	public function deliver(Order $order)
	{
		$this->authorize('deliver', $order);
		if ($order->status == 'shipped') {
			$order->status = 'delivered';
			$order->delivered_at = now();
			$order->save();
		}
		return $this->show($order);
	}

	public function complete(Order $order)
	{
		$this->authorize('complete', $order);
		if ($order->status == 'delivered') {
			$order->status = 'completed';
			$order->completed_at = now();
			$order->save();
		}
		return $this->show($order);
	}

	public function cancel(Order $order)
	{
		$this->authorize('cancel', $order);
		if ($order->status == 'created' || $order->status == 'confirmed') {
			$order->status = 'closed';
			$order->reason = 'cancelled by customer';
			$order->closed_at = now();
			$order->save();
		}
		return $this->show($order);
	}
}

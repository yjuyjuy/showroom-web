<?php

namespace App\Http\Controllers\api\v1;

use App\Order;
use App\Product;
use App\Address;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
	const ADDRESS = [
		'name' => 'Dope',
		'phone' => '18077223344',
		'address1' => '东区博爱四路优雅翠园',
		'address2' => '',
		'city' => '中山市',
		'state' => '广东省',
		'country' => '中国',
		'zip' => '528400',
	];
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$ITEMS_PER_PAGE = 12;

		if (request()->input('as_vendor') && $vendor = auth()->user()->vendor) {
			$query = $vendor->orders()->orderByDesc('created_at');
		} else {
			$query = auth()->user()->orders()->orderByDesc('created_at');
		}

		if (($status = request()->input('status')) && in_array($status, ['created', 'confirmed', 'paid', 'shipped'])) {
			$query->where('status', $status);
		}
		
		$total_pages = ceil($query->count() / $ITEMS_PER_PAGE);
		$page = min(max(request()->query('page', 1), 1), $total_pages);
		$orders = $query->forPage($page, $ITEMS_PER_PAGE)->get();

		return [
			'orders' => $orders->load(['product', 'product.brand', 'product.season', 'product.color', 'product.image', 'vendor']),
			'page' => $page,
			'total_pages' => $total_pages,
		];
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
		$offer = Product::find($data['product_id'])->offers()->where('vendor_id', $data['vendor_id'])->first();
		if (!array_key_exists($data['size'], $offer->prices)) {
			return ['message' => $data['size']." size is not available"];
		}
		$price = $offer->prices[$data['size']];
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
		if (auth()->user()->vendor_id == $order->vendor_id && !$order->is_direct) {
			$order->name = self::ADDRESS['name'];
			$order->phone = self::ADDRESS['phone'];
			$order->address1 = self::ADDRESS['address1'];
			$order->address2 = self::ADDRESS['address2'];
			$order->city = self::ADDRESS['city'];
			$order->state = self::ADDRESS['state'];
			$order->country = self::ADDRESS['country'];
			$order->zip = self::ADDRESS['zip'];
		}
		return $order->loadMissing(['product', 'product.brand', 'product.color', 'product.season', 'product.image', 'vendor',]);
	}

	public function confirm(Order $order)
	{
		$this->authorize('confirm', $order);
		if ($order->status == 'created') {
			$order->status = 'confirmed';
			$order->confirmed_at = now();
			$order->save();
		}
		return $this->show($order);
	}

	public function decline(Order $order)
	{
		$this->authorize('decline', $order);
		if ($order->status == 'created' || $order->status == 'confirmed') {
			$order->status = 'closed';
			$order->reason = 'out of stock';
			$order->closed_at = now();
			$order->save();
		} elseif ($order->status == 'paid') {
			// TODO: work out how to compensate customer
			return ['message' => '请联系管理员',];
		}
		return $this->show($order);
	}

	public function receivePayment(Order $order)
	{
		$this->authorize('receivePayment', $order);
		if ($order->status == 'confirmed') {
			$order->status = 'paid';
			$order->paid_at = now();
			$order->save();
		}
		return $this->show($order);
	}

	public function ship(Order $order)
	{
		$this->authorize('ship', $order);
		if ($order->status == 'paid') {
			$order->status = 'shipped';
			$order->tracking = request()->validate([
				'tracking' => 'required|string',
			])['tracking'];
			$order->shipped_at = now();
			$order->save();
		}
		return $this->show($order);
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

<?php

namespace App\Policies;

use App\Order;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
	use HandlesAuthorization;

	public function before($user, $ability)
	{
    	if ($user->is_admin) {
        	return true;
    	}
	}

	/**
	 * Determine whether the user can view any orders.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function viewAny(User $user)
	{
		//
	}

	/**
	 * Determine whether the user can view the order.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Order  $order
	 * @return mixed
	 */
	public function view(User $user, Order $order)
	{
		//
	}

	/**
	 * Determine whether the user can create orders.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function create(User $user)
	{
		//
	}

	/**
	 * Determine whether the user can update the order.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Order  $order
	 * @return mixed
	 */
	public function update(User $user, Order $order)
	{
		//
	}

	public function confirm(User $user, Order $order)
	{
		return $user->vendor == $order->seller || $user->vendor->retailer == $order->seller;
	}

	public function decline(User $user, Order $order)
	{
		return $user->vendor == $order->seller || $user->vendor->retailer == $order->seller;
	}

	public function receivePayment(User $user, Order $order)
	{
		return $user->vendor == $order->seller || $user->vendor->retailer == $order->seller;
	}

	public function pay(User $user, Order $order)
	{
		return $user->id == $order->user_id;
	}

	public function ship(User $user, Order $order)
	{
		return $user->vendor == $order->seller || $user->vendor->retailer == $order->seller;
	}
	
	public function deliver(User $user, Order $order)
	{
		return $user->id == $order->user_id;
	}
	
	public function complete(User $user, Order $order)
	{
		return $user->id == $order->user_id;
	}

	public function cancel(User $user, Order $order)
	{
		return $user->id == $order->user_id;
	}

	/**
	 * Determine whether the user can delete the order.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Order  $order
	 * @return mixed
	 */
	public function delete(User $user, Order $order)
	{
		//
	}

	/**
	 * Determine whether the user can restore the order.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Order  $order
	 * @return mixed
	 */
	public function restore(User $user, Order $order)
	{
		//
	}

	/**
	 * Determine whether the user can permanently delete the order.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Order  $order
	 * @return mixed
	 */
	public function forceDelete(User $user, Order $order)
	{
		//
	}
}

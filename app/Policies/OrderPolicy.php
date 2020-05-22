<?php

namespace App\Policies;

use App\Order;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
	use HandlesAuthorization;

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
		return $user->vendor_id == $order->vendor_id || $user->is_admin;
	}

	public function decline(User $user, Order $order)
	{
		return $user->vendor_id == $order->vendor_id || $user->is_admin;
	}

	public function receivePayment(User $user, Order $order)
	{
		return $user->vendor_id == $order->vendor_id || $user->is_admin;
	}

	public function pay(User $user, Order $order)
	{
		return $user->id == $order->user_id|| $user->is_admin;
	}

	public function ship(User $user, Order $order)
	{
		return $user->vendor_id == $order->vendor_id || $user->is_admin;
	}
	
	public function deliver(User $user, Order $order)
	{
		return $user->id == $order->user_id || $user->is_admin;
	}
	
	public function complete(User $user, Order $order)
	{
		return $user->id == $order->user_id || $user->is_admin;
	}

	public function cancel(User $user, Order $order)
	{
		return $user->id == $order->user_id || $user->is_admin;
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

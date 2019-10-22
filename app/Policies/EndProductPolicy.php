<?php

namespace App\Policies;

use App\User;
use App\EndProduct;
use Illuminate\Auth\Access\HandlesAuthorization;

class EndProductPolicy
{
	public function export(User $user)
	{
		if ($user->isSuperAdmin()) {
			return true;
		} else {
			return false;
		}
	}

	use HandlesAuthorization;

	/**
	 * Determine whether the user can view any end products.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function viewAny(User $user)
	{
		//
	}

	/**
	 * Determine whether the user can view the end product.
	 *
	 * @param  \App\User  $user
	 * @param  \App\EndProduct  $endProduct
	 * @return mixed
	 */
	public function view(User $user, EndProduct $endProduct)
	{
		//
	}

	/**
	 * Determine whether the user can create end products.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function create(User $user)
	{
		//
	}

	/**
	 * Determine whether the user can update the end product.
	 *
	 * @param  \App\User  $user
	 * @param  \App\EndProduct  $endProduct
	 * @return mixed
	 */
	public function update(User $user, EndProduct $endProduct)
	{
		//
	}

	/**
	 * Determine whether the user can delete the end product.
	 *
	 * @param  \App\User  $user
	 * @param  \App\EndProduct  $endProduct
	 * @return mixed
	 */
	public function delete(User $user, EndProduct $endProduct)
	{
		//
	}

	/**
	 * Determine whether the user can restore the end product.
	 *
	 * @param  \App\User  $user
	 * @param  \App\EndProduct  $endProduct
	 * @return mixed
	 */
	public function restore(User $user, EndProduct $endProduct)
	{
		//
	}

	/**
	 * Determine whether the user can permanently delete the end product.
	 *
	 * @param  \App\User  $user
	 * @param  \App\EndProduct  $endProduct
	 * @return mixed
	 */
	public function forceDelete(User $user, EndProduct $endProduct)
	{
		//
	}
}

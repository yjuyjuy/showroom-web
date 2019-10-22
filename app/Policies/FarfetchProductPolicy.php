<?php

namespace App\Policies;

use App\User;
use App\FarfetchProduct;
use Illuminate\Auth\Access\HandlesAuthorization;

class FarfetchProductPolicy
{
	use HandlesAuthorization;

	public function export(User $user)
	{
		if ($user->isSuperAdmin()) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Determine whether the user can view any farfetch products.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function viewAny(User $user)
	{
		//
	}

	/**
	 * Determine whether the user can view the farfetch product.
	 *
	 * @param  \App\User  $user
	 * @param  \App\FarfetchProduct  $farfetchProduct
	 * @return mixed
	 */
	public function view(User $user, FarfetchProduct $farfetchProduct)
	{
		//
	}

	/**
	 * Determine whether the user can create farfetch products.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function create(User $user)
	{
		//
	}

	/**
	 * Determine whether the user can update the farfetch product.
	 *
	 * @param  \App\User  $user
	 * @param  \App\FarfetchProduct  $farfetchProduct
	 * @return mixed
	 */
	public function update(User $user, FarfetchProduct $farfetchProduct)
	{
		//
	}

	/**
	 * Determine whether the user can delete the farfetch product.
	 *
	 * @param  \App\User  $user
	 * @param  \App\FarfetchProduct  $farfetchProduct
	 * @return mixed
	 */
	public function delete(User $user, FarfetchProduct $farfetchProduct)
	{
		//
	}

	/**
	 * Determine whether the user can restore the farfetch product.
	 *
	 * @param  \App\User  $user
	 * @param  \App\FarfetchProduct  $farfetchProduct
	 * @return mixed
	 */
	public function restore(User $user, FarfetchProduct $farfetchProduct)
	{
		//
	}

	/**
	 * Determine whether the user can permanently delete the farfetch product.
	 *
	 * @param  \App\User  $user
	 * @param  \App\FarfetchProduct  $farfetchProduct
	 * @return mixed
	 */
	public function forceDelete(User $user, FarfetchProduct $farfetchProduct)
	{
		//
	}
}

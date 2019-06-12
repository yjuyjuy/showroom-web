<?php

namespace App\Policies;

use App\User;
use App\Vendor;
use Illuminate\Auth\Access\HandlesAuthorization;

class VendorPolicy
{
	use HandlesAuthorization;

	public function before($user, $ability)
	{
		if ($user->isSuperAdmin() && $ability !== 'forceDelete') {
			return true;
		}
	}
	/**
	 * Determine whether the user can view any vendors.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function viewAny(User $user)
	{
		return false;
	}

	/**
	 * Determine whether the user can view the vendor.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Vendor  $vendor
	 * @return mixed
	 */
	public function view(User $user, Vendor $vendor)
	{
		return $vendor->user_id === $user->id;
	}

	/**
	 * Determine whether the user can create vendors.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function create(User $user)
	{
		return false;
	}

	/**
	 * Determine whether the user can update the vendor.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Vendor  $vendor
	 * @return mixed
	 */
	public function update(User $user, Vendor $vendor)
	{
		return $vendor->user_id === $user->id;
	}

	/**
	 * Determine whether the user can delete the vendor.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Vendor  $vendor
	 * @return mixed
	 */
	public function delete(User $user, Vendor $vendor)
	{
		return false;
	}

	/**
	 * Determine whether the user can restore the vendor.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Vendor  $vendor
	 * @return mixed
	 */
	public function restore(User $user, Vendor $vendor)
	{
		return false;
	}

	/**
	 * Determine whether the user can permanently delete the vendor.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Vendor  $vendor
	 * @return mixed
	 */
	public function forceDelete(User $user, Vendor $vendor)
	{
		return false;
	}
}

<?php

namespace App\Policies;

use App\User;
use App\Price;
use Illuminate\Auth\Access\HandlesAuthorization;

class PricePolicy
{
	use HandlesAuthorization;
	/**
	 * Determine whether the user can delete the price.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Price  $price
	 * @return mixed
	 */
	public function delete(User $user, Price $price)
	{
		return $user->isSuperAdmin() || ($user->id == $price->vendor->user_id);
	}
}

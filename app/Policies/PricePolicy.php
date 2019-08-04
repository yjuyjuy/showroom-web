<?php

namespace App\Policies;

use App\User;
use App\OfferPrice;
use Illuminate\Auth\Access\HandlesAuthorization;

class PricePolicy
{
		use HandlesAuthorization;

    /**
     * Determine whether the user can view any prices.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the price.
     *
     * @param  \App\User  $user
     * @param  \App\OfferPrice  $price
     * @return mixed
     */
    public function view(User $user, OfferPrice $price)
    {
        //
    }

    /**
     * Determine whether the user can create prices.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
			return $user->isSuperAdmin() || $user->vendor;
    }

    /**
     * Determine whether the user can update the price.
     *
     * @param  \App\User  $user
     * @param  \App\OfferPrice  $price
     * @return mixed
     */
    public function update(User $user, OfferPrice $price)
    {
			return $user->isSuperAdmin() || ($user->id == $price->vendor->user_id);
    }

    /**
     * Determine whether the user can delete the price.
     *
     * @param  \App\User  $user
     * @param  \App\OfferPrice  $price
     * @return mixed
     */
    public function delete(User $user, OfferPrice $price)
    {
			return $user->isSuperAdmin() || ($user->id == $price->vendor->user_id);
    }

    /**
     * Determine whether the user can restore the price.
     *
     * @param  \App\User  $user
     * @param  \App\OfferPrice  $price
     * @return mixed
     */
    public function restore(User $user, OfferPrice $price)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the price.
     *
     * @param  \App\User  $user
     * @param  \App\OfferPrice  $price
     * @return mixed
     */
    public function forceDelete(User $user, OfferPrice $price)
    {
      return false;
    }
}

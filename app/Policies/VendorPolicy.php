<?php

namespace App\Policies;

use App\User;
use App\Vendor;
use Illuminate\Auth\Access\HandlesAuthorization;

class VendorPolicy
{
	use HandlesAuthorization;

	public function view(User $user, Vendor $vendor)
	{
		return $user->following_vendors->contains($vendor);
	}
}

<?php

namespace App\Policies;

use App\User;
use App\Image;
use Illuminate\Auth\Access\HandlesAuthorization;

class ImagePolicy
{
	use HandlesAuthorization;
	public function update(User $user)
	{
		return $user->isSuperAdmin();
	}
}

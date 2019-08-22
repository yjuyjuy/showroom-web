<?php

namespace App\Policies;

use App\User;
use App\TaobaoShop;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaobaoPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can update the taobao shop.
     *
     * @param  \App\User  $user
     * @param  \App\TaobaoShop  $taobaoShop
     * @return mixed
     */
    public function update(User $user, TaobaoShop $taobaoShop)
    {
      return $user->isSuperAdmin();
    }
}

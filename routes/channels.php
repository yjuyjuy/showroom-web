<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use App\Order;


Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('vendor.{id}', function ($user, $id) {
    return (int) $user->vendor_id === (int) $id;
});
Broadcast::channel('retailer.{id}', function ($user, $id) {
    return (int) $user->vendor->retailer_id === (int) $id;
});

<?php

namespace App\Http\Controllers\api\v3\shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChannelController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();
        $channels = ['user.' . $user->id];
        if ($user->vendor) {
            $channels[] = 'vendor.' . $user->vendor->id;
            if ($user->vendor->retailer) {
                $channels[] = 'retailer.' . $user->vendor->retailer->id;
            }
        }
        return $channels;
    }
}

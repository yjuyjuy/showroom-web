<?php

namespace App\Http\Controllers\api\v3\shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HelperController extends Controller
{
    const GEO_API = 'https://api.ipgeolocationapi.com/geolocate';
    public function ip(Request $request) {
        return ['ip' => $request->header('x-forwarded-for')];
    }

    public function geolocate(Request $request) {
        return Http::get(self::GEO_API."/{$request->header('x-forwarded-for')}")->json();
    }
}

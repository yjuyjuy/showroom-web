<?php

namespace App\Http\Controllers\api\v3\shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HelperController extends Controller
{
    const GEO_API = 'https://api.ipgeolocationapi.com/geolocate';
    const SERVERS = [
        'CN' => 'https://cn.notdopebxtch.com',
        'US' => 'https://us.notdopebxtch.com',
        'default' => 'https://us.notdopebxtch.com',
    ];
    public function ip(Request $request)
    {
        return $request->header('x-forwarded-for') ?? $request->ip();
    }

    public function geolocate(Request $request)
    {
        return Http::get(self::GEO_API . "/{$this->ip($request)}")->json();
    }

    public function server(Request $request)
    {
        $countryCode = $this->geolocate($request)['alpha2'];
        if (!empty($countryCode) && array_key_exists($countryCode, self::SERVERS)) {
            return self::SERVERS[$countryCode];
        } else {
            return self::SERVERS['default'];
        }
    }
}

<?php

namespace App\Http\Controllers\api\v3\shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    public function __invoke()
    {
        return [
            'min' => config('app.version.min'),
            'latest' => config('app.version.latest'),
            // for backward compatibility
            'current' => config('app.version.latest'),
        ];
    }
}
